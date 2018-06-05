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

use Uspdev\Replicado\Pessoa;

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

        // Mostrar apenas os não devolvidos: 'dataDevolucao'=>null
        $emprestimos = $em->getRepository('App:Emprestimo')->findby(['dataDevolucao'=>null],array('material' => 'ASC'));

        // Replicado
        $replicado = [];
        if(getenv('USAR_REPLICADO')== 'true') {         
            foreach($emprestimos as $emprestimo) {
                $codpes = $emprestimo->getCodpes();
                if(!empty($codpes)) {
                    $replicado[$codpes] = $this->pessoaUSP($codpes);
                }
            }
        }
       
             

/*          if(!$pessoa & !$cracha){
                $this->addFlash('danger', sprintf('Armário não emprestado! Pessoa %s não encontrada na USP',$emprestimo->getPessoaUsp()));
                return $this->redirectToRoute('emprestimo_pessoausp_new');
            }
*/


/*
            // Verificar se a pessoa já não possui armário emprestado
            foreach($armarios_indisponiveis as $x){
                if($emprestimo->getPessoaUsp() == $x->getPessoaUsp()){
                    $this->addFlash('danger', sprintf('Armário não emprestado! Pois o usuário(a) %s 
                        já tem o armário %s emprestado.',$emprestimo->getPessoaUsp(),$x->getArmario()));
                    return $this->redirectToRoute('emprestimo_pessoausp_new');
                }
            }

            $armario_em_quesao = $em->getRepository('AppBundle:Armario')->findOneById($emprestimo->getArmario());
            if(!$armario_em_quesao->getAtivo()){
                $this->addFlash('danger', sprintf('Armário não emprestado! 
                    Pois armário %s está desativado, favor escolha outro armário!',$emprestimo->getArmario()));
                return $this->redirectToRoute('emprestimo_pessoausp_new');
            }
            
            // Quando a pessoa não tem vínculo nenhum com a FFLCH, pegar nome da tabela CATR_CRACHA
            $nome = $pessoa ? $pessoa[0]['PESSOA_nompes'] : $cracha[0]['CATR_CRACHA_nompescra'];
*/   

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
            if(!$this->estaDisponivel($emprestimo)){
                $this->addFlash('danger', sprintf('Erro: Item %s já está emprestado para outra pessoa!',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_usp');
            }
            if(!$emprestimo->getMaterial()->getAtivo()){
                $this->addFlash('danger', sprintf('Erro: Item %s não pode ser emprestado no momento, pois está desativado.',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_usp');
            }

            $emprestimo->setDataEmprestimo(new \DateTime());
            $emprestimo->setCreatedBy($this->getUser());
            $em->persist($emprestimo);
            $em->flush();

            return $this->render('emprestimo/show.html.twig', array(
                'emprestimo' => $emprestimo,
            ));
        }
     
        return $this->render('emprestimo/usp.html.twig', array(
            'emprestimo' => $emprestimo,
//            'armarios_indisponiveis' => $armarios_indisponiveis,
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

            if(!$this->estaDisponivel($emprestimo)){
                $this->addFlash('danger', sprintf('Erro: Item %s já está emprestado para outra pessoa!',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_visitante');
            }
            if(!$emprestimo->getMaterial()->getAtivo()){
                $this->addFlash('danger', sprintf('Erro: Item %s não pode ser emprestado no momento, pois está desativado.',
                                $emprestimo->getMaterial()->getCodigo()));
                return $this->redirectToRoute('emprestimo_visitante');
            }

          
/*

            // Verificar se a pessoa já não possui armário emprestado
            foreach($armarios_indisponiveis as $x){
                if($emprestimo->getPessoaExterna() == $x->getPessoaExterna()){
                    $this->addFlash('danger', sprintf('Armário não emprestado! Pois o usuário(a) %s 
                        já tem o armário %s emprestado.',$emprestimo->getPessoaExterna(),$x->getArmario()));
                    return $this->redirectToRoute('emprestimo_pessoaexterna_new');
                }
            }

            $armario_em_quesao = $em->getRepository('AppBundle:Armario')->findOneById($emprestimo->getArmario());

            if(!$armario_em_quesao->getAtivo()){
                $this->addFlash('danger', sprintf('Armário não emprestado! 
                    Pois armário %s está desativado, favor escolha outro armário!',$emprestimo->getArmario()));
                    return $this->redirectToRoute('emprestimo_pessoaexterna_new');
            }
*/
            $emprestimo->setDataEmprestimo(new \DateTime());
            $emprestimo->setCreatedBy($this->getUser());
            $em->persist($emprestimo);
            $em->flush();

            return $this->render('emprestimo/show.html.twig', array(
                'emprestimo' => $emprestimo,
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
            'emprestimo' => $emprestimo,
            'form' => $form->createView(),
        ));

    }

   /********************************** Utils Functions *****************************************/

    public function emprestados()
    {
        $em = $this->getDoctrine()->getManager();
        $emprestados = $em->getRepository('App:Emprestimo')->findby(['dataDevolucao'=>null],array('material' => 'ASC'));
        return $emprestados;
    }

    public function estaDisponivel($check)
    {
        $emprestados = $this->emprestados();

        foreach($emprestados as $emprestado){
            if($check->getMaterial() === $emprestado->getMaterial()){
                return false;
            }
        }
        return true;
    }

    public function pessoaUSP($codpes)
    {
        if(!empty(Pessoa::dump($codpes))) {
            return Pessoa::dump($codpes)['nompes'] .' - '. Pessoa::email($codpes);
        }
        else { 
            if( getenv('USAR_TABELA_CRACHA') == 'true') {
                return Pessoa::cracha($codpes)['nompescra'];
            }
        }
    }
}
