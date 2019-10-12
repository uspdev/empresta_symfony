<?php

namespace App\Controller;

use App\Entity\Emprestimo;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Uspdev\Wsfoto;
use Uspdev\Replicado\Pessoa;
use App\Empresta\Utils;

/**
 * Emprestimo controller.
 * @Security("is_granted('ROLE_USER','ROLE_ADMIN')")
 */
class EmprestimoController extends Controller
{
    /**
     * Lists all emprestimo entities.
     *
     * @Route("/", name="emprestimo_index", methods="GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $emprestimos = $this->emprestimos();

        // Replicado
        $replicado = [];
        if(getenv('USAR_REPLICADO')== 'true') {         
            foreach($emprestimos as $emprestimo) {
                $codpes = $emprestimo->getCodpes();
                if(!empty($codpes)) {
                    $replicado[$codpes] = Utils::pessoaUSP($codpes);
                }
            }
        }

        return $this->render('emprestimo/index.html.twig', array(
            'emprestimos' => $emprestimos,
            'replicado' => $replicado,
        ));
    }

    /**
     * Creates a new emprestimo entity.
     *
     * @Route("/emprestimo/usp", name="emprestimo_usp", methods="GET|POST")
     */
    public function newActionUsp(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $emprestimo = new Emprestimo();

        $form = $this->createForm('App\Form\EmprestimoUspType', $emprestimo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Armário já emprestado
            if(!$this->estaDisponivel($emprestimo)){
                $this->addFlash('danger', sprintf('Erro: Item %s já está emprestado para outra pessoa!',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_usp');
            }

            // Armário desativado
            if(!$emprestimo->getMaterial()->getAtivo()){
                $this->addFlash('danger', sprintf('Erro: Item %s não pode ser emprestado no momento, pois está desativado.',$emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_usp');
            }

            // Não permite empréstimo de dois materiais do mesmo tipo
            foreach($this->emprestimos() as $x){
                if( ($emprestimo->getCodpes() == $x->getCodpes()) & 
                    ($emprestimo->getMaterial()->getTipo() == $x->getMaterial()->getTipo())
                  ){
                    $this->addFlash('danger', sprintf('Item não emprestado! Pois o usuário(a) %s 
                        já tem %s emprestado.',
                        $emprestimo->getCodpes(),$x->getMaterial()->getTipo()));
                    return $this->redirectToRoute('emprestimo_usp');
                }
            }  

            // Replicado
            $replicado = [];
            if( getenv('USAR_REPLICADO') == 'true') {         
                $codpes = $emprestimo->getCodpes();
                if(!empty($codpes)) {
                    $replicado[$codpes] = Utils::pessoaUSP($codpes);
                }
            }

            // Wsfoto
            $wsfoto = '';
            if( getenv('USAR_WSFOTO') == 'true') {         
                $codpes = $emprestimo->getCodpes();
                if(!empty($codpes)) {
                    if(Wsfoto::obter($codpes)) {
                        $wsfoto = Wsfoto::obter($codpes);
                    }
                }
            }

            $emprestimo->setDataEmprestimo(new \DateTime());
            $emprestimo->setCreatedBy($this->getUser());
            $em->persist($emprestimo);
            $em->flush();

            return $this->render('emprestimo/show.html.twig', array(
                'emprestimo' => $emprestimo,
                'replicado' => $replicado,
                'wsfoto' => $wsfoto,
            ));
        }
     
        return $this->render('emprestimo/usp.html.twig', array(
            'emprestimo' => $emprestimo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new emprestimo entity.
     *
     * @Route("/emprestimo/visitante", name="emprestimo_visitante", methods="GET|POST")
     */
    public function newActionVisitante(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $emprestimo = new Emprestimo();
        $form = $this->createForm('App\Form\EmprestimoVisitanteType', $emprestimo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // verifica se item já não está emprestado
            if(!$this->estaDisponivel($emprestimo)){
                $this->addFlash('danger', sprintf('Erro: Item %s já está emprestado para outra pessoa!',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_visitante');
            }
        
            // item desativado
            if(!$emprestimo->getMaterial()->getAtivo()){
                $this->addFlash('danger', sprintf('Erro: Item %s não pode ser emprestado no momento,    pois está desativado.',$emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_visitante');
            }

            // Não permite empréstimo de dois materiais do mesmo tipo
            foreach($this->emprestimos() as $x){
                if( ($emprestimo->getVisitante() == $x->getVisitante()) &
                    ($emprestimo->getMaterial()->getTipo() == $x->getMaterial()->getTipo())
                  ){
                    $this->addFlash('danger', sprintf('Item não emprestado! Pois o usuário(a) %s 
                        já tem %s emprestado.',
                        $emprestimo->getVisitante(),$x->getMaterial()->getTipo()));
                    return $this->redirectToRoute('emprestimo_visitante');
                }
            }   
  
            $emprestimo->setDataEmprestimo(new \DateTime());
            $emprestimo->setCreatedBy($this->getUser());
            $em->persist($emprestimo);
            $em->flush();

            return $this->render('emprestimo/show.html.twig', array(
                'emprestimo' => $emprestimo,
                'replicado' => [],
                'wsfoto' => [],
            ));
        }
  
        return $this->render('emprestimo/visitante.html.twig', array(
            'emprestimo' => $emprestimo,
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/emprestimo/devolucao", name="emprestimo_devolucao")     
     * @Method({"GET", "POST"})
     */
    public function devolucaoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $emprestimo =  new Emprestimo();
        $form = $this->createForm('App\Form\DevolucaoType',$emprestimo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Verifica se o armário está de fato emprestado
            $emprestado = $em->getRepository('App:Emprestimo')
                             ->findOneby(['dataDevolucao'=>null,'material'=>$emprestimo->getMaterial()]);

            if(!$emprestado)
            {
                $this->addFlash('danger', sprintf('Atenção: o item %s não está emprestado!',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_devolucao');
            }

            $emprestado->setDataDevolucao(new \DateTime());
            $em->flush();
            $this->addFlash('success', sprintf('Item %s devolvido com Sucesso!',
                            $emprestimo->getMaterial()->getCodigo()));
            return $this->redirectToRoute('emprestimo_devolucao');
        }

        return $this->render('emprestimo/devolucao.html.twig', array(
            'emprestimo'            => $emprestimo,
            'form'                  => $form->createView(),
        ));

    }

    /**
     * @Route("/emprestimo/{id}", name="emprestimo_show", methods="GET")
     */
    public function show(Emprestimo $emprestimo): Response
    {
        // Replicado
        $replicado = [];
        if( getenv('USAR_REPLICADO') == 'true') {         
            $codpes = $emprestimo->getCodpes();
            if(!empty($codpes)) {
                $replicado[$codpes] = Utils::pessoaUSP($codpes);
            }
        }

        // Wsfoto
        $wsfoto = '';
        if( getenv('USAR_WSFOTO') == 'true') {         
            $codpes = $emprestimo->getCodpes();
            if(!empty($codpes)) {
                if(Wsfoto::obter($codpes)) {
                    $wsfoto = Wsfoto::obter($codpes);
                }
            }
        }

        return $this->render('emprestimo/show.html.twig', [
            'emprestimo' => $emprestimo,
            'replicado' => $replicado,
            'wsfoto' => $wsfoto,
        ]);
    }

    /**
     * Relatório
     * Recebe dois parâmetros dataInicio e dataFim
     * Por padrão os últimos 90 dias
     * 
     * @Route("/relatorio", name="relatorio", methods="GET")
     */
    public function relatorio(): Response
    {        
        // Últimos 90 dias
        $objDataAtual = new \DateTime(date('Y-m-d H:i:s'));
        $dataFim = $objDataAtual->format('Y-m-d H:i:s');
        $objDataAtual->modify('-90 day');
        $dataInicio = $objDataAtual->format('Y-m-d H:i:s');

        // Empréstimos realizados
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Emprestimo');
        $query = $repository->createQueryBuilder('a')
            ->innerJoin('a.material', 'g')
            ->where("a.dataEmprestimo BETWEEN :dataInicio AND :dataFim")
            ->setParameter('dataInicio', $dataInicio)    
            ->setParameter('dataFim', $dataFim)        
            ->orderBy('a.dataEmprestimo', 'DESC')
            ->getQuery();
        $emprestimos = $query->execute();

        // Replicado
        $replicado = [];
        if(getenv('USAR_REPLICADO')== 'true') {         
            foreach($emprestimos as $emprestimo) {
                $codpes = $emprestimo->getCodpes();
                if(!empty($codpes)) {
                    $replicado[$codpes] = Utils::pessoaUSP($codpes);
                }
            }
        }

        return $this->render('emprestimo/relatorio.html.twig', [
            'emprestimos'   => $emprestimos,
            'replicado'     => $replicado,
        ]);
    }    
   /********************************** Utils Functions *****************************************/
   //TODO:  Move this funtions to outside this class!

    public function emprestimos()
    {
        $em = $this->getDoctrine()->getManager();
        $emprestimos = $em->getRepository('App:Emprestimo')->findby(['dataDevolucao'=>null],array('material' => 'ASC'));
        return $emprestimos;
    }

    public function estaDisponivel($check)
    {
        $emprestimos = $this->emprestimos();

        foreach($emprestimos as $emprestado){
            if($check->getMaterial() === $emprestado->getMaterial()){
                return false;
            }
        }
        return true;
    }
}
