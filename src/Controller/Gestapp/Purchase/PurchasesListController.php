<?php

namespace App\Controller\Gestapp\Purchase;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Admin\Member;
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
            'purchases'=> $member->getPurchases()
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
            'purchases'=> $member->getPurchases()
        ]);
    }
}