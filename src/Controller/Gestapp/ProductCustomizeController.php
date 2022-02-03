<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Product;
use App\Entity\Gestapp\ProductCustomize;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductCustomizeController extends AbstractController
{
    /**
     * Ajouter une personnalisation sur le produit en cour
     * Envoie en Json - Réponse en Json
     * @Route("/gestapp/product/{id}/customize", name="op_gestapp_product_customize_new")
     */
    public function new(Request $request, Product $product)
    {
        $name = $request->get('nameCustomer');
        $session = $request->cookies->get('PHPSESSID');
        $sessid = $request->get('sessid');
        //dd($product);

        $productCustomize = new ProductCustomize();
        $productCustomize->setName($name);
        $productCustomize->setUuid($sessid);
        $productCustomize->setProduct($product);

        //dd($productCustomize);
        if($sessid == $session){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productCustomize);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message'=> "Le produit a été personnalisé."
            ], 200);
        }

        return $this->json([
            'code' => 403,
            'message'=> "Une erreur s'est produite. Vous êtes rester inactif trop longtemps."
        ], 403);

    }
}