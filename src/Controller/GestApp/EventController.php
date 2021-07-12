<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Event;
use App\Form\GestApp\EventType;
use App\Repository\GestApp\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/gest/app/event/", name="op_gestapp_event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
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
        $events = $this->getDoctrine()->getRepository(Event::class)->ListAllEventPublish();

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
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_gestapp_event_index');
        }

        return $this->render('gest_app/event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
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
}
