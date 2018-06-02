<?php

namespace App\Controller;

use App\Entity\TipoMaterial;
use App\Form\TipoMaterialType;
use App\Repository\TipoMaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/tipo_material")
 */
class TipoMaterialController extends Controller
{
    /**
     * @Route("/search", name="tipo_material_search", methods="GET", defaults={"_format"="json"})
     */
/*    public function search(Request $request)
    {
        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('App:TipoMaterial')->findLikeNome($q);

        return $this->render('tipo_material/search.json.twig', ['results' => $results]);
    }
*/
    /**
     * @Route("/get/{id}", name="tipo_material_get", methods="GET|POST", defaults={"_format"="json"})
     */
/*    public function get($id = null)
    {
        $tipo = $this->getDoctrine()->getRepository('App:TipoMaterial')->find($id);

        return $this->json($tipo->getNome());
    }
*/
    /**
     * @Route("/", name="tipo_material_index", methods="GET")
     */
    public function index(TipoMaterialRepository $tipoMaterialRepository): Response
    {
        return $this->render('tipo_material/index.html.twig', ['tipo_materials' => $tipoMaterialRepository->findAll()]);
    }

    /**
     * @Route("/new", name="tipo_material_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $tipoMaterial = new TipoMaterial();
        $form = $this->createForm(TipoMaterialType::class, $tipoMaterial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $tipoMaterial->setCreatedBy($this->getUser());
            $em->persist($tipoMaterial);
            $em->flush();

            return $this->redirectToRoute('tipo_material_index');
        }

        return $this->render('tipo_material/new.html.twig', [
            'tipo_material' => $tipoMaterial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_material_show", methods="GET")
     */
    public function show(TipoMaterial $tipoMaterial): Response
    {
        return $this->render('tipo_material/show.html.twig', ['tipo_material' => $tipoMaterial]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_material_edit", methods="GET|POST")
     */
    public function edit(Request $request, TipoMaterial $tipoMaterial): Response
    {
        $form = $this->createForm(TipoMaterialType::class, $tipoMaterial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_material_edit', ['id' => $tipoMaterial->getId()]);
        }

        return $this->render('tipo_material/edit.html.twig', [
            'tipo_material' => $tipoMaterial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_material_delete", methods="DELETE")
     */
    public function delete(Request $request, TipoMaterial $tipoMaterial): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoMaterial->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoMaterial);
            $em->flush();
        }

        return $this->redirectToRoute('tipo_material_index');
    }
}
