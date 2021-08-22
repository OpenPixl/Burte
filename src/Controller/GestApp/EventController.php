<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Event;
use App\Entity\GestApp\EventGal;
use App\Form\GestApp\EventType;
use App\Repository\GestApp\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/gest/app/event/", name="op_gestapp_event_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAdmin(EventRepository $eventRepository): Response
    {

        $user = $this->getUser()->getId();
        return $this->render('gest_app/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/gest/app/event2/", name="op_gestapp_event_index2", methods={"GET"})
     */
    public function indexUser(EventRepository $eventRepository): Response
    {

        $user = $this->getUser()->getId();
        return $this->render('gest_app/event/index.html.twig', [
            'events' => $eventRepository->findBy(array("author"=> $user)),
        ]);
    }

    /**
     * @Route("/gest/app/ListAllEventPublish/", name="op_gestapp_event_ListAllEventPublish", methods={"GET"})
     */
    public function ListAllEventPublish(): Response
    {
        $date = new \DateTimeImmutable();
        $current = $date->format('Y-m-d');
        $events = $this->getDoctrine()->getRepository(Event::class)->ListAllEventPublish($current);

        return $this->render('gest_app/event/event.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/gest/app/ListEventPublishByMember/{idmember}", name="op_gestapp_event_ListEventPublishByMember", methods={"GET"})
     */
    public function ListEventPublishByMember($idmember): Response
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->ListEventPublishMember($idmember);

        return $this->render('gest_app/event/eventsbyuser.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/gest/app/event/new", name="op_gestapp_event_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $member = $this->getUser();
        $event = new Event();
        $event->setAuthor($member);
        $event->setIsValidBy(1);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            // partie de code pour envoyer un email au membre recommandé
            $email = (new Email())
                ->from('postmaster@openpixl.fr')
                ->to('xavier.burke@openpixl.fr')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('JUSTàFaire - une nouvelle recommandation a été émise depuis le site')
                //->text('Sending emails is fun again!')
                ->html('
                    <h1>Just à Faire<small> - Nouvelle évenement créer</small></h1>
                    ');
            $mailer->send($email);

            return $this->redirectToRoute('op_gestapp_event_index');
        }

        return $this->render('gest_app/event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gest/app/event/{id}", name="op_gestapp_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('gest_app/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/gest/app/event/{id}/edit", name="op_gestapp_event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $idevent = $event->getId();
        $galeries = $this->getDoctrine()->getRepository(EventGal::class)->findBy(array('event'=>$idevent));

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_gestapp_event_index');
        }

        return $this->render('gest_app/event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'galeries' => $galeries
        ]);
    }

    /**
     * @Route("/gest/app/event/{id}", name="op_gestapp_event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_gestapp_event_index');
    }

    /**
     * Suppression d'une ligne index.php
     * @Route("/gest/app/event/delevent/{id}", name="op_gestapp_event_delevent", methods={"POST"})
     */
    public function DelEvent(Request $request, Event $event) : Response
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->json([
            'code'=> 200,
            'message' => "L'évènenemt a été supprimé"
        ], 200);
    }

    /**
     * Permet de mettre en menu la poge ou non
     * @Route("/admin/event/valid/{id}", name="op_gestapp_event_isvalid")
     */
    public function jsMenuvalid(Event $event, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $isvalid = $event->getIsValidBy();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isvalid == true){
            $event->setIsValidBy(0);
            $em->flush();
            return $this->json(['code'=> 200, 'message' => "L'évènement est dépublié du site"], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $event->setIsValidBy(1);
        $em->flush();
        return $this->json(['code'=> 200, 'message' => "L'évènement est publié sur le site"], 200);
    }

    public function historyOfEventPublish()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(array('isPublish'=> 1));

        return $this->render('gest_app/event/history.html.twig', [
            'events' => $events,
        ]);
    }
}
