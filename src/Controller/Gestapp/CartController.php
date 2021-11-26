<?php

namespace App\Controller\Gestapp;

use App\Repository\Gestapp\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/Gestapp/cart/{id}", name="op_webapp_cart_add", requirements={"id":"\d+"})
     */
    public function cart($id, Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if(!$product){
            throw $this->createNotFoundException("Le produit portant l'identifiant $id n'existe pas.");
        }
        // On chercher dans la session si le panier existe.
        // On creer si le panier n'existe pas.
        $cart = $request->getSession()->get('cart', []);

        if(array_key_exists($id, $cart)){                           // On teste si dans le tableau panier si "Id" existe,
            $cart[$id]++;                                           // si c'est le cas ajoute la quantité,
        } else {
            $cart[$id]=1;                                           // sinon, on ajoute 1 à l'Id dans le panier.
        }
        $request->getSession()->set('cart', $cart);                 // on push le panier dans la session.
        dd($request->getSession()->get('cart'));
    }

}
