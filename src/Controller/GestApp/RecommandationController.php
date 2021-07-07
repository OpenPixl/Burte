<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Recommandation;
use App\Form\GestApp\RecommandationType;
use App\Repository\GestApp\RecommandationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class RecommandationController extends AbstractController
{
    /**
     * @Route("/op_admin/gestapp/recommandation/", name="op_gestapp_recommandation_index", methods={"GET"})
     */
    public function index(RecommandationRepository $recommandationRepository): Response
    {
        return $this->render('gest_app/recommandation/index.html.twig', [
            'recommandations' => $recommandationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/new", name="op_gestapp_recommandation_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $recommandation = new Recommandation();
        $form = $this->createForm(RecommandationType::class, $recommandation);
        $recommandation->setRecoState('noRead');
        $recommandation->setAuthor($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recommandation);
            $entityManager->flush();
            // partie de code pour envoyer un email
            $email = (new Email())
                ->from('postmaster@openpixl.fr')
                ->to($recommandation->getMember())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('JUSTàFaire - une nouvelle recommandation pour vous')
                //->text('Sending emails is fun again!')
                ->html('<p>test</p>');
            $mailer->send($email);

            return $this->redirectToRoute('op_gestapp_recommandation_index');
        }

        return $this->render('gest_app/recommandation/new.html.twig', [
            'recommandation' => $recommandation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/{id}", name="op_gestapp_recommandation_show", methods={"GET"})
     */
    public function show(Recommandation $recommandation): Response
    {
        return $this->render('gest_app/recommandation/show.html.twig', [
            'recommandation' => $recommandation,
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/{id}/edit", name="op_gestapp_recommandation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recommandation $recommandation): Response
    {
        $isRead = $recommandation->getIsRead();
        $form = $this->createForm(RecommandationType::class, $recommandation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gest_app_recommandation_index');
        }

        return $this->render('gest_app/recommandation/edit.html.twig', [
            'recommandation' => $recommandation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/{id}", name="op_gestapp_recommandation_delete", methods={"POST"})
     */
    public function delete(Request $request, Recommandation $recommandation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recommandation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recommandation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_gestapp_recommandation_index');
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/byuser", name="op_gestapp_recommandation_byuser", methods={"GET"})
     */
    public function byUser(RecommandationRepository $recommandationRepository): Response
    {
        $user = $this->getUser();
        $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUser($user);
        return $this->render('gest_app/recommandation/byuser.html.twig', [
            'recommandations' => $recommandations,
        ]);
    }
}
