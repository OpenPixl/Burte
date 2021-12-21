<?php

namespace App\Controller\Gestapp;

use App\Cart\CartService;
use App\Form\Gestapp\CartConfirmationType;
use App\Repository\Gestapp\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    protected $productRepository;
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/gestapp/cart/{id}", name="op_webapp_cart_add", requirements={"id":"\d+"})
     */
    public function cart($id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        // teste si le produit existe dans la liste de produit.
        if(!$product){
            throw $this->createNotFoundException("Le produit portant l'identifiant $id n'existe pas.");
        }

        $this->cartService->increment($id);

        $this->addFlash('success', "Le produit a bien été ajouté au panier");

        if($request->query->get('returnToCart')){
            return $this->redirectToRoute('op_webapp_cart_showcartjson');
        }

        return $this->redirectToRoute('op_gestapp_product_show', [
            'id' => $id
        ]);
    }

    /**
     * Liste les produits inclus dans le panier
     * @Route("/webapp/cart/show", name="op_webapp_cart_showcart")
     */
    public function showCart()
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $this->cartService->getDetailedCartItem();
        $total = $this->cartService->getTotal();

        return $this->render('gestapp/cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    /**
     * Liste les produits inclus dans le panier
     * @Route("/webapp/cart/showjson", name="op_webapp_cart_showcartjson")
     */
    public function showCartJson()
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $this->cartService->getDetailedCartItem();
        $total = $this->cartService->getTotal();

        // Retourne une réponse en json
        return $this->json([
            'code'          => 200,
            'message'       => "Le produit a été correctement supprimé.",
            'liste'         => $this->renderView('gestapp/cart/include/_liste.html.twig', [
                'items' => $detailedCart,
                'total' => $total,
                'confirmationForm' => $form->createView()
            ])
        ], 200);
    }

    /**
     * @Route("/gestapp/cart/decrement/{id}", name="op_webapp_cart_decrement", requirements={"id":"\d+"})
     */
    public function decrementeCart($id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        // teste si le produit existe dans la liste de produit.
        if(!$product){
            throw $this->createNotFoundException("Le produit portant l'identifiant $id n'existe pas et ne peut être diminué dans le panier.");
        }

        $this->cartService->decrement($id);

        $this->addFlash('success', "Le produit a bien été diminué dans le panier.");

        if($request->query->get('returnToCart')){
            return $this->redirectToRoute('op_webapp_cart_showcartjson');
        }

        return $this->redirectToRoute('op_gestapp_product_show', [
            'id' => $id
        ]);
    }

    /**
     * Supprime un produit du panier
     * @Route("/webapp/cart/del/{id}", name="op_webapp_cart_delete", requirements={"id":"\d+"})
     */
    public function deleteProduct($id, ProductRepository $productRepository, CartService $cartService)
    {
        $product = $productRepository->find($id);

        if(!$product){
            throw $this->createNotFoundException("LE produit $id n'existe pas et ne peut pas être supprimé !");
        }

        $this->cartService->remove($id);
        $this->addFlash("success", "le produit a bien été supprimé du panier");

        return $this->redirectToRoute("op_webapp_cart_showcart");

    }

}
