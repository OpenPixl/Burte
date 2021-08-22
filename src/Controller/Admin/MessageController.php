<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Message;
use App\Form\Admin\MessageType;
use App\Repository\Admin\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/admin/message/", name="op_admin_message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();

        return $this->render('admin/message/index.html.twig', [
            'messages' => $messageRepository->MessagesByUser($user),
        ]);
    }

    /**
     * @Route("/admin/message/new", name="op_admin_message_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // On récupère l'utilisateur courant
        $user= $this->getUser();

        //Préparation de la nouvelle entité
        $message = new Message();
        $message->setFollow(uniqid());
        $message->setAuthor($user);

        // Préparation du formulaire
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('admin_message_index');
        }

        return $this->render('admin/message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/message/{id}", name="op_admin_message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('admin/message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/message/{id}/edit", name="op_admin_message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_message_index');
        }

        return $this->render('admin/message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/message/{id}", name="op_admin_message_delete", methods={"POST"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_message_index');
    }
}
