<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
*/
class AdminController extends AbstractController
{
    /**
     * @Route("/op_admin/dashboard/index", name="op_admin_dashboard_index")
     */
    public function index(): Response
    {
        $members = $this->getDoctrine()->getRepository(Member::class)->findAll();
        return $this->render('admin/admin/index.html.twig', [
            'members' => $members,
        ]);
    }
}
