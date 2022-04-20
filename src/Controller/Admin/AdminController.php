<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Annonce;
use App\Entity\Admin\Member;
use App\Entity\Admin\Message;
use App\Entity\Gestapp\Event;
use App\Entity\Gestapp\Purchase;
use App\Entity\Gestapp\Recommandation;
use Knp\Bundle\SnappyBundle\Snappy\Response\SnappyResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
*/
class AdminController extends AbstractController
{

    /**
     * @Route("/opadmin/dashboard/index", name="op_admin_dashboard_index")
     */
    public function index(): Response
    {
        $user= $this->getUser();
        //dd($hasAccess);
        if($hasAccess = $this->isGranted('ROLE_EDITOR') or $hasAccess = $this->isGranted('ROLE_ADMIN')){

            $members = $this->getDoctrine()->getRepository(Member::class)->findBy(array('type' => 'client'));
            $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findAll();
            $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
            $purchases = $this->getDoctrine()->getRepository(Purchase::class)->findAll();
            $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
            $messages = $this->getDoctrine()->getRepository(Message::class)->MessagesByUser($user);
            return $this->render('admin/admin/index.html.twig', [
                'members' => $members,
                'recommandations' => $recommandations,
                'events' => $events,
                'annonces' => $annonces,
                'messages' => $messages,
                'purchases' => $purchases
            ]);
        }
        else{
            return $this->redirectToRoute('op_webapp_public_dashboard_client');
        }
    }
}
