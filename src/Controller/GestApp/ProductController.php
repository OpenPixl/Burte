<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Product;
use App\Form\GestApp\ProductType;
use App\Repository\GestApp\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/gestapp/product/", name="op_gestapp_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('gest_app/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/gestapp/product/new", name="op_gestapp_product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('op_gestapp_product_index');
        }

        return $this->render('gest_app/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestapp/product/{id}", name="op_gestapp_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('gest_app/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/gestapp/product/{id}/edit", name="op_gestapp_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_gestapp_product_index');
        }

        return $this->render('gest_app/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestapp/product/{id}", name="op_gestapp_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gest_app_product_index');
    }

    /**
     * Permet d'activer ou de désactiver la mise en ligne d'un produit
     * @Route("/gestapp/product/online/{id}/json", name="op_gestapp_product_online")
     */
    public function jsonline(Product $product, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $isonline = $product->getIsOnLine();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isonline == true){
            $product->setIsOnLine(0);
            $em->flush();
            return $this->json([
                'code'      => 200,
                'message'   => "Le produit est n'est plus publié en ligne."
            ], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $product->setIsOnLine(1);
        $em->flush();
        return $this->json([
            'code'          => 200,
            'message'       => 'Le produit est mis en ligne.'
        ], 200);
    }

    /**
     * Permet d'activer ou de désactiver la mise en ligne d'un produit
     * @Route("/gestapp/product/jsstar/{id}/json", name="op_gestapp_product_star")
     */
    public function jsstar(Product $product, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $isstar = $product->getIsStar();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isstar == true){
            $product->setIsStar(0);
            $em->flush();
            return $this->json([
                'code'      => 200,
                'message'   => "Le produit est n'est plus publié en vedette."
            ], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $product->setIsStar(1);
        $em->flush();
        return $this->json([
            'code'          => 200,
            'message'       => 'Le produit est publié en vedette.'
        ], 200);
    }

    /**
     * Espace E-Commerce : Liste les produits
     * @Route("/gestapp/product/alldispo", name="op_gestapp_product_alldispo", methods={"POST"})
     */
    public function ListAllProductDispo()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array('isOnLine' => 1));

        return $this->render('gest_app/product/listallproductdispo.html.twig',[
            'products' => $products
        ]);
    }
}
