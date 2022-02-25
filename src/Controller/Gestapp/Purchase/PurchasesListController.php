<?php

namespace App\Controller\Gestapp\Purchase;

use App\Entity\Gestapp\Purchase;
use App\Repository\Gestapp\PurchaseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Admin\Member;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends abstractController
{
    /**
     * @Route("/webapp/purchases/", name="op_webapp_purchases_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     */
    public function index()
    {
        /** @var Member */
        $member = $this->getUser();

        return $this->render('gestapp/purchase/index.html.twig',[
            'purchases'=> $member->getPurchases(),
            'hide' => 0
        ]);
    }

    /**
     * @Route("/opadmin/purchases/", name="op_admin_purchases_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à l'administration")
     */
    public function listAdmin()
    {
        /** @var Member */
        $member = $this->getUser();

        return $this->render('gestapp/purchase/list.html.twig',[
            'purchases'=> $member->getPurchases(),
            'hide' => 0
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/purchases/byuserReceipt/{hide}", name="op_gestapp_purchases_byuserreceipt", methods={"GET"})
     */
    public function byUserReceipt($hide, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getUser();
        $purchases = $this->getDoctrine()->getRepository(Purchase::class)->findByUserReceipt($user);
        return $this->render('gestapp/purchase/byuserReceipt.html.twig', [
            'purchases' => $purchases,
            'hide' => $hide
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/purchases/byuserSend/{hide}", name="op_gestapp_purchases_byusersend", methods={"GET"})
     */
    public function byUserSend($hide,PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getUser();
        $purchases = $this->getDoctrine()->getRepository(Purchase::class)->findByUserSend($user);
        $hide = 1;
        return $this->render('gestapp/purchase/byuserSend.html.twig', [
            'purchases' => $purchases,
            'hide' => $hide
        ]);
    }
}