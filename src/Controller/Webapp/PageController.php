<?php

namespace App\Controller\Webapp;

use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
use App\Form\Webapp\PageType;
use App\Repository\Webapp\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/webapp/page/", name="op_webapp_page_index", methods={"GET"})
     */
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('webapp/page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/webapp/page/new", name="op_webapp_page_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $member = $this->getUser();
        $page = new Page();
        $page->setAuthor($member);
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            $section = new Section();
            $section->setTitle('nouvelle section');
            $section->setDescription('Espace présentant sur le dashboard, le role de la section créée das la page.');
            $section->setContentType('One_article');
            $section->setPage($page);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_page_index');
        }

        return $this->render('webapp/page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * affiche la page en front office selon le slug
     * @Route("/webapp/page/{slug}", name="op_webapp_page_slug", methods={"GET"})
     */
    public function page($slug) : response
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findbyslug($slug);

        return $this->render('webapp/page/page.html.twig');
    }

    /**
     * @Route("/webapp/page/{id}", name="op_webapp_page_show", methods={"GET"})
     */
    public function show(Page $page): Response
    {
        return $this->render('webapp/page/show.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/webapp/page/{id}/edit", name="op_webapp_page_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_page_index');
        }

        return $this->render('webapp/page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/page/{id}", name="op_webapp_page_delete", methods={"POST"})
     */
    public function delete(Request $request, Page $page): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_webapp_page_index');
    }

    /**
     * Affiche une page du site par son slug
     *
     * @param PageRepository $pageRepository
     * @param $slug
     * @return Response
     * @Route("/page/{slug}", name="op_webapp_page_slug")
     */
    public function pagebyslug(PageRepository $pageRepository, $slug): Response
    {
        $page = $pageRepository->findOneBy(array('slug' => $slug));

        return $this->render('webapp/page/page.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * Permet d'activer ou de désactiver la publication
     * @Route("/webapp/page/publish/{id}/json", name="op_webapp_page_publish")
     */
    public function jspublish(Page $page, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $ispublish = $page->getIsPublish();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($ispublish == true){
            $page->setIsPublish(0);
            $em->flush();
            return $this->json([
                'code'      => 200,
                'message'   => 'La page est dépubliée'
            ], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $page->setIsPublish(1);
        $em->flush();
        return $this->json([
            'code'          => 200,
            'message'       => 'La page est publiée'
        ], 200);
    }

    /**
     * Permet de mettre en menu la poge ou non
     * @Route("/webapp/page/menu/{id}/json", name="op_webapp_page_menu")
     */
    public function jsMenu(Page $page, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $ismenu = $page->getIsMenu();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($ismenu == true){
            $page->setIsMenu(0);
            $em->flush();
            return $this->json(['code'=> 200, 'message' => 'La page est dépublié des menus'], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $page->setIsMenu(1);
        $em->flush();
        return $this->json(['code'=> 200, 'message' => 'La page est publié dans les menus'], 200);
    }

    /**
     * Permet de up la position de la page
     * @Route("/webapp/page/upposition/{id}/json", name="op_webapp_page_position_up")
     */
    public function upPosition(Page $page, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $position = $page->getIsMenu();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
    }

    /**
     * Permet de down la position de la page
     * @Route("/webapp/page/downposition/{id}", name="op_webapp_page_menuposition_down")
     */
    public function downPosition(Page $page, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $ismenu = $page->getIsMenu();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vopus n'êtes pas connecté"
        ], 403);
    }
}
