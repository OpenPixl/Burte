<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Product;
use App\Entity\Gestapp\ProductCustomize;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ProductCustomizeController extends AbstractController
{
    /**
     * Ajouter une personnalisation sur le produit en cour
     * Envoie en Json - Réponse en Json
     * @Route("/gestapp/product/{id}/customize", name="op_gestapp_product_customize_new")
     */
    public function new(Request $request, Product $product, EntityManagerInterface $em)
    {
        $name = $request->get('nameCustomer');
        $session = $request->getSession()->get('name_uuid');

        //dd($session);

        $productCustomize = new ProductCustomize();
        $productCustomize->setName($name);
        $productCustomize->setUuid($session);
        $productCustomize->setProduct($product);

        //dd($productCustomize);

        $em = $this->getDoctrine()->getManager();
        $em->persist($productCustomize);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message'=> "Le produit a été personnalisé."
        ], 200);

    }
}