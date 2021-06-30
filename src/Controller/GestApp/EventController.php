<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Event;
use App\Form\GestApp\EventType;
use App\Repository\GestApp\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('gest_app/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
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
     * @Route("/gest/app/event/new", name="op_gestapp_event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $member = $this->getUser();
        $event = new Event();
        $event->setAuthor($member);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

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
