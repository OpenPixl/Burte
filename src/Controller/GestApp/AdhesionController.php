<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Adhesion;
use App\Form\GestApp\AdhesionType;
use App\Repository\GestApp\AdhesionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**

 */
class AdhesionController extends AbstractController
{
    /**
     * @Route("/gestapp/adhesion", name="op_gestapp_adhesion_index", methods={"GET"})
     */
    public function index(AdhesionRepository $adhesionRepository): Response
    {
        return $this->render('gest_app/adhesion/index.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/gestapp/adhesion/new", name="op_gestapp_adhesion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('gest_app_adhesion_index');
        }

        return $this->render('gest_app/adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestapp/adhesion/new2", name="op_gestapp_adhesion_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_public_index');
        }

        return $this->render('gest_app/adhesion/new2.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestapp/adhesion/add", name="op_gestapp_adhesion_add", methods={"GET","POST"})
     */
    public function addAdhesion(Request $request): Response
    {
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_public_index');
        }

        return $this->render('gest_app/adhesion/add.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestapp/adhesion/{id}", name="op_gestapp_adhesion_show", methods={"GET"})
     */
    public function show(Adhesion $adhesion): Response
    {
        return $this->render('gest_app/adhesion/show.html.twig', [
            'adhesion' => $adhesion,
        ]);
    }

    /**
     * @Route("/gestapp/adhesion/{id}/edit", name="op_gestapp_adhesion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adhesion $adhesion): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gest_app_adhesion_index');
        }

        return $this->render('gest_app/adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestapp/adhesion/{id}", name="op_gestapp_adhesion_delete", methods={"POST"})
     */
    public function delete(Request $request, Adhesion $adhesion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adhesion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gest_app_adhesion_index');
    }
}
