<?php

namespace App\Controller\Gestapp\Purchase;

use App\Cart\CartService;
use App\Entity\Gestapp\Purchase;
use App\Entity\Gestapp\PurchaseItem;
use App\Form\Gestapp\CartConfirmationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseConfirmationController extends AbstractController
{

    protected $cartService;
    protected $em;

    public function __construct(CartService $cartService, EntityManagerInterface $em)
    {
        $this->cartService = $cartService;
        $this->em = $em;
    }
    /**
     * @Route("webapp/purchase/confirm", name="op_webapp_purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être inscrit sur la plateforme pour confirmer votre commande")
     */
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);

        if(!$form->isSubmitted()) {
            $this->addFlash('warning', 'vous devez compléter le formulaire');
            return $this->redirectToRoute('op_webapp_cart_showcart');
        }

        $user = $this->getUser();

        $cartItems = $this->cartService->getDetailedCartItem();
        if(count($cartItems) === 0){
            $this->addFlash('warning', 'le panier est vide, impossible de commander');
            return $this->redirectToRoute('op_webapp_cart_showcart');
        }

        /** @var Purchase */
        $purchase = $form->getData();
        //dd($purchase);
        $purchase
            ->setCustomer($user)
            ->setPurchasedAt(new DateTime())
            ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);

        foreach($this->cartService->getDetailedCartItem() as $cartItem){
            $purchaseItem = new PurchaseItem;
            $purchaseItem
                ->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductQty($cartItem->qty)
                ->setProductPrice($cartItem->product->getPrice())
                ->setTotalItem($cartItem->getTotal())
            ;
            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
        $this->cartService->emptyCart();
        $this->addFlash('success', "La commande est enrigistré");
        return $this->redirectToRoute('op_webapp_purchases_index');
    }
}