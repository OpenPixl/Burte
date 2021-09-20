<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Society;
use App\Form\Admin\SocietyType;
use App\Repository\Admin\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/society")
 */
class SocietyController extends AbstractController
{
    /**
     * @Route("/", name="admin_society_index", methods={"GET"})
     */
    public function index(SocietyRepository $societyRepository): Response
    {
        return $this->render('admin/society/bottom.html.twig', [
            'societies' => $societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_society_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $society = new Society();
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($society);
            $entityManager->flush();

            return $this->redirectToRoute('admin_society_index');
        }

        return $this->render('admin/society/new.html.twig', [
            'society' => $society,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_society_show", methods={"GET"})
     */
    public function show(Society $society): Response
    {
        return $this->render('admin/society/show.html.twig', [
            'society' => $society,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_society_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Society $society): Response
    {
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_society_index');
        }

        return $this->render('admin/society/edit.html.twig', [
            'society' => $society,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_society_delete", methods={"POST"})
     */
    public function delete(Request $request, Society $society): Response
    {
        if ($this->isCsrfTokenValid('delete'.$society->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($society);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_society_index');
    }
}
