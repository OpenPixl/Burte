<?php

namespace App\Controller\Gestapp\Purchase;

use Twig\Environment;
use App\Entity\Admin\Member;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
            throw new AccessDeniedException('Vous devez être connecter');
        }
        $html = $this->twig->render('gestapp/purchase/index.html.twig', [
           'purchases' => $member->getPurchases()
        ]);
        return new Response($html);
    }
}