<?php

namespace App\Controller;

use App\Entity\Visitante;
use App\Form\VisitanteType;
use App\Repository\VisitanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER','ROLE_ADMIN')")
 * @Route("/visitante")
 */
class VisitanteController extends Controller
{

    /**
     * @Route("/search", name="visitante_search", methods="GET", defaults={"_format"="json"})
     */
    public function search(VisitanteRepository $visitanteRepository, Request $request): Response
    {
        $qs = $request->query->get('q', $request->query->get('term', ''));
        $visitantes = $visitanteRepository->findLike($qs);

        return $this->render('visitante/search.json.twig', ['visitantes' => $visitantes]);
    }

    /**
     * @Route("/get/{id}", name="visitante_get", methods="GET", defaults={"_format"="json"})
     */
    public function getAction($id = null, VisitanteRepository $visitanteRepository): Response
    {
        if (null === $visitante = $visitanteRepository->find($id)) {
            throw $this->createNotFoundException();
        }
        return $this->json($visitante->getNome());
    }

    /**
     * @Route("/", name="visitante_index", methods="GET")
     */
    public function index(VisitanteRepository $visitanteRepository): Response
    {
        return $this->render('visitante/index.html.twig', ['visitantes' => $visitanteRepository->findAll()]);
    }

    /**
     * @Route("/new", name="visitante_new", methods="GET|POST")
     */
/*    public function new(Request $request): Response
    {
        $visitante = new Visitante();
        $form = $this->createForm(VisitanteType::class, $visitante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $visitante->setCreatedBy($this->getUser());
            $em->persist($visitante);
            $em->flush();

            return $this->redirectToRoute('visitante_index');
        }

        return $this->render('visitante/new.html.twig', [
            'visitante' => $visitante,
            'form' => $form->createView(),
        ]);
    }
*/

    /**
     * @Route("/new", name="visitante_new", methods="GET|POST|PUT")
     */
    public function new(Request $request, VisitanteRepository $visitanteRepository): Response
    {
        $visitante = new Visitante();
        
        $form = $this->createForm('App\Form\VisitanteType', $visitante, [
            'method' => 'PUT',
            'action' => $this->generateUrl('visitante_new'),
        ]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $visitanteRepository->add($visitante);
            if ($request->isXmlHttpRequest()) {
                return $this->json(['id' => $visitante->getId(), 'name' => $visitante->getNome(), 'type' => 'visitante']);
            } else {
                $this->addFlash('success', 'Visitante Cadastrado!');

                return $this->redirectToRoute('visitante_index');
            }
        }

        return $this->render('visitante/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="visitante_show", methods="GET")
     */
    public function show(Visitante $visitante): Response
    {
        return $this->render('visitante/show.html.twig', ['visitante' => $visitante]);
    }

    /**
     * @Route("/{id}/edit", name="visitante_edit", methods="GET|POST")
     */
    public function edit(Request $request, Visitante $visitante): Response
    {
        $form = $this->createForm(VisitanteType::class, $visitante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visitante_edit', ['id' => $visitante->getId()]);
        }

        return $this->render('visitante/edit.html.twig', [
            'visitante' => $visitante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visitante_delete", methods="DELETE")
     */
    public function delete(Request $request, Visitante $visitante): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visitante->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($visitante);
            $em->flush();
        }

        return $this->redirectToRoute('visitante_index');
    }
}
