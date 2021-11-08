<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\ProductUnit;
use App\Form\GestApp\ProductUnitType;
use App\Repository\GestApp\ProductUnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductUnitController extends AbstractController
{
    /**
     * @Route("/opadmin/product/unit/", name="op_gestapp_product_unit_index", methods={"GET"})
     */
    public function index(ProductUnitRepository $productUnitRepository): Response
    {
        return $this->render('gest_app/product_unit/index.html.twig', [
            'product_units' => $productUnitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/opadmin/product/unit/new", name="op_gestapp_product_unit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productUnit = new ProductUnit();
        $form = $this->createForm(ProductUnitType::class, $productUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productUnit);
            $entityManager->flush();

            return $this->redirectToRoute('gest_app_product_unit_index');
        }

        return $this->render('gest_app/product_unit/new.html.twig', [
            'product_unit' => $productUnit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/opadmin/product/unit/new2", name="op_gestapp_product_unit_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if(!$data)
        {
            $productUnit = new ProductUnit();
            $form = $this->createForm(ProductUnitType::class, $productUnit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($productUnit);
                $entityManager->flush();

                return $this->redirectToRoute('op_gestapp_product_nature_index');
            }

            return $this->render('gest_app/product_category/new2.html.twig', [
                'product_category' => $productUnit,
                'form' => $form->createView(),
            ]);
        }else{
            $name = $data['name'];
            $productUnit = new ProductUnit();
            $productUnit->setName($name);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productUnit);
            $entityManager->flush();

            $unit = $productUnit->getName();

            return $this->json([
                'code' => 200,
                'unit' => $unit,
                'message' => "Une nature de produit a été ajoutée."
            ], 200);
        }

    }

    /**
     * @Route("/opadmin/product/unit/{id}", name="op_gestapp_product_unit_show", methods={"GET"})
     */
    public function show(ProductUnit $productUnit): Response
    {
        return $this->render('gest_app/product_unit/show.html.twig', [
            'product_unit' => $productUnit,
        ]);
    }

    /**
     * @Route("/opadmin/product/unit/{id}/edit", name="op_gestapp_product_unit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductUnit $productUnit): Response
    {
        $form = $this->createForm(ProductUnitType::class, $productUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gest_app_product_unit_index');
        }

        return $this->render('gest_app/product_unit/edit.html.twig', [
            'product_unit' => $productUnit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/opadmin/product/unit/{id}", name="op_gestapp_product_unit_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductUnit $productUnit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productUnit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productUnit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gest_app_product_unit_index');
    }
}
