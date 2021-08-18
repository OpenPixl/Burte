<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Annonce;
use App\Form\Admin\AnnonceType;
use App\Repository\Admin\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/opadmin/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="op_admin_annonce_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('admin/annonce/index.html.twig', [
            'annonce' => $annonceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="op_admin_annonce_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $annonce = new Annonce();
        $annonce->setAuthor($user);
        $form = $this->createForm(annonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_annonce_index');
        }

        return $this->render('admin/annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newaxios", name="op_admin_annonce_newaxios", methods={"GET","POST"})
     */
    public function newaxios(Request $request): Response
    {
        // on recupère les données transmises
        $data = json_decode($request->getContent(), true);

        //
        $user = $this->getUser();
        $annonce = new Annonce();
        $annonce->setTitle($data['title']);
        $annonce->setContent($data['content']);
        $annonce->setAuthor($user);
        $annonce->setPublishAt(new \DateTime($data['publishAt']));
        $annonce->setDispublishAt(new \DateTime($data['dispublishAt']));
        //dd($annonce);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($annonce);
        $entityManager->flush();

        return $this->json([
            'code'=> 200,
            'message' => "Ajout d'annonce réalisée."
        ], 200);
    }

    /**
     * @Route("/{id}", name="op_admin_annonce_show", methods={"GET"})
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render('admin/annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="op_admin_annonce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce): Response
    {
        $form = $this->createForm(annonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_annonce_index');
        }

        return $this->render('admin/annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_annonce_delete", methods={"POST"})
     */
    public function delete(Request $request, Annonce $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_annonce_index');
    }

    /**
     * Affichage des annonces sur le dashboard
     * @Route("/dashboard", name="op_admin_annonces_dashboard", methods={"GET"})
     */
    public function publishDashboard(AnnonceRepository $annonceRepository)
    {
        // Préparation des variables nécéssaires
        $date = new \DateTimeImmutable();
        $current = $date->format('Y-m-d');

        // on récupère seulement les annonces dont la date du jour est dans l'intervalle de publication.
        $annonces = $annonceRepository->publishDashboard($current);

        // on retourne la vue
        return $this->render('admin/annonce/dashboard.html.twig',[
            'annonces' => $annonces,
            'current' => $current
        ]);
    }
}
