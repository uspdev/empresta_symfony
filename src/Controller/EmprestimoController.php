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

/**
 * Emprestimo controller.
 * @Security("is_granted('ROLE_USER','ROLE_ADMIN')")
 * @Route("emprestimo")
 */
class EmprestimoController extends Controller
{
    /**
     * Lists all emprestimo entities.
     *
     * @Route("/", name="emprestimo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // Mostrar apenas os não devolvidos: 'dataDevolucao'=>null
        $emprestimos = $em->getRepository('App:Emprestimo')->findby(['dataDevolucao'=>null],array('material' => 'ASC'));

        return $this->render('emprestimo/index.html.twig', array(
            'emprestimos' => $emprestimos,
        ));
    }

    /**
     * Creates a new emprestimo entity.
     *
     * @Route("/usp", name="emprestimo_usp", methods="GET|POST")
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



            // Verifica se usuário existe na tabela pessoa
 //           $pessoa = $this->get('Fflch\Replicado\Pessoa')->pessoaByCodpes($emprestimo->getPessoaUsp());

            // Verifica se usuário existe na tabela cracha
//            $cracha = $this->get('Fflch\Replicado\Pessoa')->cracha($emprestimo->getPessoaUsp());

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
            $emprestimo->setDataEmprestimo(new \DateTime());
            $emprestimo->setCreatedBy($this->getUser());
            $em->persist($emprestimo);
            $em->flush();

            return $this->redirectToRoute('index', array('id' => $emprestimo->getId()));
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
     * @Route("/visitante", name="emprestimo_visitante", methods="GET|POST")
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

            //return $this->redirectToRoute('emprestimo_show', array('id' => $emprestimo->getId()));
            return $this->redirectToRoute('index');
        }

        
        return $this->render('emprestimo/visitante.html.twig', array(
            'emprestimo' => $emprestimo,
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/devolucao", name="emprestimo_devolucao")     
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

    /**
     * Deletes a emprestimo entity.
     *
     * @Route("/{id}/delete", name="emprestimo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Emprestimo $emprestimo)
    {
        $form = $this->createDeleteForm($emprestimo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($emprestimo);
            $em->flush();
        }

        return $this->redirectToRoute('emprestimo_index');
    }

    /**
     * Creates a form to delete a emprestimo entity.
     *
     * @param Emprestimo $emprestimo The emprestimo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Emprestimo $emprestimo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('emprestimo_delete', array('id' => $emprestimo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
}
