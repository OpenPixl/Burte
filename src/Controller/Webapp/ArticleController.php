<?php

namespace App\Controller\Webapp;

use App\Entity\Webapp\Article;
use App\Form\Webapp\ArticleType;
use App\Repository\Webapp\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/webapp/article/", name="op_webapp_article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('webapp/article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/webapp/article/new", name="op_webapp_article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $member = $this->getUser();
        $article = new Article();
        $article->setAuthor($member);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_article_index');
        }

        return $this->render('webapp/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/article/{id}", name="op_webapp_article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('webapp/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/webapp/article/{id}/edit", name="op_webapp_article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_article_edit', ['id' => $article->getId()]);
        }

        return $this->render('webapp/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/article/{id}", name="op_webapp_article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_webapp_article_index');
    }

    /**
     * Affiche un seul article en Front
     * @Route("/webapp/article/one/{id}", name="op_webapp_article_one")
     */
    public function oneArticle($id) : Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->OneArticle($id);
        //dd($article);

        return $this->render('webapp/article/one.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/webapp/carousel/", name="op_webapp_article_carousel", methods={"GET"})
     */
    public function carousel(ArticleRepository $articleRepository): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->carousel();
        return $this->render('webapp/article/carousel.html.twig', [
            'articles' => $articles
        ]);
    }
}
