<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\ProductNature;
use App\Form\Gestapp\ProductNature1Type;
use App\Form\Gestapp\ProductNatureType;
use App\Repository\Gestapp\ProductNatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class ProductNatureController extends AbstractController
{
    /**
     * @Route("/opadmin/product/nature/", name="op_Gestapp_product_nature_index", methods={"GET"})
     */
    public function index(ProductNatureRepository $productNatureRepository): Response
    {
        return $this->render('Gestapp/product_nature/index.html.twig', [
            'product_natures' => $productNatureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/opadmin/product/nature/new", name="op_Gestapp_product_nature_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productNature = new ProductNature();
        $form = $this->createForm(ProductNatureType::class, $productNature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productNature);
            $entityManager->flush();

            return $this->redirectToRoute('Gestapp_product_nature_index');
        }

        return $this->render('Gestapp/product_nature/new.html.twig', [
            'product_nature' => $productNature,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/opadmin/product/nature/new2", name="op_Gestapp_product_nature_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if(!$data)
        {
            $productNature = new ProductNature();
            $form = $this->createForm(ProductNatureType::class, $productNature);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($productNature);
                $entityManager->flush();

                return $this->redirectToRoute('op_Gestapp_product_nature_index');
            }

            return $this->render('Gestapp/product_category/new2.html.twig', [
                'product_category' => $productNature,
                'form' => $form->createView(),
            ]);
        }else{
            $name = $data['name'];
            $productNature = new ProductNature();
            $productNature->setName($name);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productNature);
            $entityManager->flush();

            $nature = $productNature->getName();

            return $this->json([
                'code' => 200,
                'nature' => $nature,
                'message' => "Une nature de produit a été ajoutée."
            ], 200);
        }

    }

    /**
     * @Route("/opadmin/product/nature//{id}", name="op_Gestapp_product_nature_show", methods={"GET"})
     */
    public function show(ProductNature $productNature): Response
    {
        return $this->render('Gestapp/product_nature/show.html.twig', [
            'product_nature' => $productNature,
        ]);
    }

    /**
     * @Route("/opadmin/product/nature//{id}/edit", name="op_Gestapp_product_nature_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductNature $productNature): Response
    {
        $form = $this->createForm(ProductNatureType::class, $productNature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('Gestapp_product_nature_index');
        }

        return $this->render('Gestapp/product_nature/edit.html.twig', [
            'product_nature' => $productNature,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/opadmin/product/nature//{id}", name="op_Gestapp_product_nature_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductNature $productNature): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productNature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productNature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Gestapp_product_nature_index');
    }
}
