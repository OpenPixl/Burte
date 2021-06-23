<?php

namespace App\Controller\Webapp;

use App\Entity\Webapp\MediaOne;
use App\Form\Webapp\MediaOneType;
use App\Repository\Webapp\MediaOneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webapp/mediaone")
 */
class MediaOneController extends AbstractController
{
    /**
     * @Route("/webapp/mediaone/", name="op_webapp_media_one_index", methods={"GET"})
     */
    public function index(MediaOneRepository $mediaOneRepository): Response
    {
        return $this->render('webapp/media_one/index.html.twig', [
            'media_ones' => $mediaOneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/webapp/mediaone/new", name="op_webapp_media_one_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mediaOne = new MediaOne();
        $form = $this->createForm(MediaOneType::class, $mediaOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mediaOne);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_media_one_index');
        }

        return $this->render('webapp/media_one/new.html.twig', [
            'media_one' => $mediaOne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/mediaone/{id}", name="op_webapp_media_one_show", methods={"GET"})
     */
    public function show(MediaOne $mediaOne): Response
    {
        return $this->render('webapp/media_one/show.html.twig', [
            'media_one' => $mediaOne,
        ]);
    }

    /**
     * @Route("/webapp/mediaone/vue/{idsection}", name="op_webapp_media_one_vue", methods={"GET"})
     */
    public function vue($idsection): Response
    {
        $mediaOne = $this->getDoctrine()->getRepository(MediaOne::class)->findBySection($idsection);
        
        return $this->render('webapp/media_one/vue.html.twig', [
            'media_one' => $mediaOne,
        ]);
    }

    /**
     * @Route("/webapp/mediaone/{id}/edit", name="op_webapp_media_one_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MediaOne $mediaOne): Response
    {
        $form = $this->createForm(MediaOneType::class, $mediaOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_media_one_index');
        }

        return $this->render('webapp/media_one/edit.html.twig', [
            'media_one' => $mediaOne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/mediaone/{id}", name="op_webapp_media_one_delete", methods={"POST"})
     */
    public function delete(Request $request, MediaOne $mediaOne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mediaOne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mediaOne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('webapp_media_one_index');
    }
}
