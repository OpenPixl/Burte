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

    public function increment(int $id){

        // On chercher dans la session si le panier existe.
        // On creer si le panier n'existe pas.
        $cart = $this->getCart();

        if(array_key_exists($id, $cart)){                           // On teste si dans le tableau panier si "Id" existe,
            $cart[$id]++;                                           // si c'est le cas ajoute la quantité,
        } else {
            $cart[$id]=1;                                           // sinon, on ajoute 1 à l'Id dans le panier.
        }

        $this->setCart($cart);
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
                continue;                                           // ne force pas la boucle suir l'incrémentation du produit mais passe à l'item suivnat
            }
            $total += $product->getPrice()*$qty;
        }
        return $total;
    }

    public function getDetailedCartItem() : array
    {
        $detailedCart =[];                                            // on prépare un tableau du futur panier détaillé

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