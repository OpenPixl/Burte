<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/security/login/index", name="security_login_index")
     */
    public function index(): Response
    {
        return $this->render('security/login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
