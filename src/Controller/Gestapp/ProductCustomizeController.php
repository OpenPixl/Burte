<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Product;
use App\Entity\Gestapp\ProductCustomize;
use App\Entity\Gestapp\productFormat;
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
        // récupération des données du formaulaire ProductCustomize et intégration dans la table
        $data = json_decode($request->getContent(), true);
        //dd($data);
        $idformat = $data['format'];
        $sessid = $data['sessid'];
        if(isset($data['name'])){
            $name = $data['name'];
        }else{$name = null;}

        $format = $em->getRepository(productFormat::class)->find($idformat);

        // Alimentation de la table
        $productCustomize = new ProductCustomize();
        if(!$name){
            $productCustomize->setFormat($format);
            $productCustomize->setUuid($sessid);
            $productCustomize->setProduct($product);
        }else{
            $productCustomize->setName($name);
            $productCustomize->setFormat($format);
            $productCustomize->setUuid($sessid);
            $productCustomize->setProduct($product);
        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($productCustomize);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message'=> "Les informations sur le produit ont été correctement."
        ], 200);

    }
}