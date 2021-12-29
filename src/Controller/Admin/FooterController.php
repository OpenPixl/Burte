<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController
{
    /**
     * @Route("/admin/footer", name="op_admin_template_footer_bottom")
     */
    public function FooterBottom(): Response
    {
        $parameter = $this->getDoctrine()->getRepository(Parameter::class)->find(1);
        return $this->render('admin/footer/bottom.html.twig', [
            'parameter' => $parameter
        ]);
    }

    /**
     * @Route("/admin/footer", name="op_admin_template_footer_adminBlock")
     */
    public function FooterBlock()
    {
        return $this->render('admin/footer/block.html.twig');
    }
}
