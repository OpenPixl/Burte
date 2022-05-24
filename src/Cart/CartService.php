<?php

namespace App\Cart;

use App\Repository\Gestapp\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    protected function getCart() : array
    {
        return $this->session->get('cart', []);
    }

    protected function setCart(array $cart)
    {
        return $this->session->set('cart', $cart);
    }

    protected function saveCart(array $cart){
        $this->session->set('cart', $cart);
    }

    public function emptyCart(){
        $this->saveCart([]);
    }

    public function increment(int $id){

        $cart = $this->getCart();                                       // récupération du panier par le service CartService

        if(!array_key_exists($id, $cart)){                              // si dans le tableau panier si "Id" n'existe pas,
            $cart[$id] = 0;                                             // alors le panier ajout 0 en quantité du panier,
        }
        $cart[$id]++;                                                   // et automatiquement, on incrémente de 1 le produit dans le panier.

        $this->setCart($cart);                                          // on insére en session le panier modifié
    }

    public function decrement(int $id){

        // On chercher dans la session si le panier existe.
        // On creer si le panier n'existe pas.
        $cart = $this->getCart();

        if(!array_key_exists($id, $cart)){                              // On teste si dans le tableau panier si "Id" existe,
            return;                                                     // si c'est le cas ajoute la quantité,
        } else {
            if($cart[$id] === 1) {                                      // sinon, on ajoute 1 à l'Id dans le panier.
                $this->remove($id);
                return;
            }
            $cart[$id]--;
        $this->session->set('cart', $cart);
        }

        $this->setCart($cart);
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);

        $this->setCart($cart);
    }

    public function getTotal()
    {
        $total = 0;
        foreach($this->getCart() as $id => $qty)
        {
            $product = $this->productRepository->find($id);
            if(!$product)
            {
                continue;                                           // ne force pas la boucle sur l'incrémentation du produit mais passe à l'item suivnat
            }
            $total += $product->getPrice()*$qty;
        }
        return $total;
    }

    /**
     * @return CartItem[]
     */
    public function getDetailedCartItem() : array
    {
        $detailedCart = [];                                           // on prépare un tableau du futur panier détaillé

        foreach($this->getCart() as $id => $qty)
        {
            $product = $this->productRepository->find($id);
            if(!$product)
            {
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);
        }
        return $detailedCart;
    }



}