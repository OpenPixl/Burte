<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Product;
use App\Form\Gestapp\ProductType;
use App\Repository\Gestapp\ProductRepository;
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
     * @Route("/Gestapp/product/", name="op_Gestapp_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('Gestapp/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Gestapp/product/new", name="op_Gestapp_product_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('op_Gestapp_product_index');
        }

        return $this->render('Gestapp/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webbapp/product/{id}", name="op_Gestapp_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('Gestapp/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/Gestapp/product/{id}/edit", name="op_Gestapp_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_Gestapp_product_index');
        }

        return $this->render('Gestapp/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Gestapp/product/{id}", name="op_Gestapp_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Gestapp_product_index');
    }

    /**
     * Permet d'activer ou de désactiver la mise en ligne d'un produit
     * @Route("/Gestapp/product/online/{id}/json", name="op_Gestapp_product_online")
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
     * @Route("/Gestapp/product/jsstar/{id}/json", name="op_Gestapp_product_star")
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
     * @Route("/Gestapp/product/alldispo", name="op_Gestapp_product_alldispo", methods={"POST"})
     */
    public function ListAllProductDispo()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->listAllProduct();

        return $this->render('Gestapp/product/listallproductdispo.html.twig',[
            'products' => $products
        ]);
    }

    /**
     * Espace E-Commerce : Liste les produits
     * @Route("/Gestapp/product/oneCat/{idcat}", name="op_Gestapp_product_onecat", methods={"POST"})
     */
    public function ListOneCatProduct($idcat)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->oneCategory($idcat);

        return $this->render('Gestapp/product/listonecatproduct.html.twig',[
            'products' => $products
        ]);
    }
}
