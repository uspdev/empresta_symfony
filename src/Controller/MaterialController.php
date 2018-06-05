<?php

namespace App\Controller;

use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/material")
 */
class MaterialController extends Controller
{
    /**
     * @Route("/", name="material_index", methods="GET")
     */
    public function index(MaterialRepository $materialRepository): Response
    {
        return $this->render('material/index.html.twig', ['materials' => $materialRepository->findAll()]);
    }

    /**
     * @Route("/new", name="material_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $material->setCreatedBy($this->getUser());
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="material_show", methods="GET|POST")
     */
    public function show(Material $material,Request $request): Response
    {
        // Empréstimos realizados
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Emprestimo');
        $query = $repository->createQueryBuilder('a')
            ->innerJoin('a.material', 'g')
            ->where('g.id = :material_id')
            ->setParameter('material_id', $material->getId())
            ->orderBy('a.dataDevolucao', 'ASC')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        // barcode
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        //$barcode = base64_encode($generator->getBarcode($material->getCodigo(), $generator::TYPE_CODE_39));
        $barcode = base64_encode($generator->getBarcode($material->getCodigo(), $generator::TYPE_CODE_128));

        // parameters to template
        return $this->render('material/show.html.twig', [
            'material'    => $material,
            'barcode'     => $barcode,
            'pagination'  => $pagination,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="material_edit", methods="GET|POST")
     */
    public function edit(Request $request, Material $material): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('material_edit', ['id' => $material->getId()]);
        }

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="material_delete", methods="DELETE")
     */
    public function delete(Request $request, Material $material): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
        }

        return $this->redirectToRoute('material_index');
    }
}
