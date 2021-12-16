<?php

namespace App\Controller\Gestapp\Purchase;

use App\Entity\Admin\Member;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchasesListController extends abstractController
{
    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $router, Environment $twig)
    {
        $this->security = $security;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @Route("/webapp/purchases/", name="op_webapp_purchases_index")
     */
    public function index()
    {
        /**
         * @var Member
         */
        $member = $this->security->getUser();
        if(!$member){
            $url = $this->router->generate('op_webapp_public_homepage');
            return new RedirectResponse($url);
        }
        $html = $this->twig->render('purchase/index.html.twig', [
           'purchases' => $member->getPurchases()
        ]);
        return new Response($html);
    }
}