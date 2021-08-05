<?php

namespace App\Controller\GestApp;

use App\Entity\GestApp\Recommandation;
use App\Form\GestApp\RecommandationType;
use App\Repository\GestApp\RecommandationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\SerializerInterface;


class RecommandationController extends AbstractController
{
    /**
     * @Route("/op_admin/gestapp/recommandation/", name="op_gestapp_recommandation_index", methods={"GET"})
     */
    public function index(RecommandationRepository $recommandationRepository): Response
    {
        $user = $this->getUser();
        $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUser($user);
        return $this->render('gest_app/recommandation/index.html.twig', [
            'recommandations' => $recommandations,
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/reload", name="op_gestapp_recommandation_indexreload", methods={"GET"})
     */
    public function indexReload(RecommandationRepository $recommandationRepository): Response
    {
        $user = $this->getUser();
        $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUser($user);
        return $this->render('gest_app/recommandation/reload.html.twig', [
            'recommandations' => $recommandations,
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
        $recommandation->setIsFirstView(0);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recommandation);
            $entityManager->flush();

            //récupération de l'adresss mail pour le membre recommandé
            $membre = $recommandation->getMember();
            $email = $membre->getEmail();

            // partie de code pour envoyer un email au membre recommandé
            $email = (new Email())
                ->from('postmaster@openpixl.fr')
                ->to($email)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('JUSTàFaire - une nouvelle recommandation vous attends dans votre espace')
                //->text('Sending emails is fun again!')
                ->html('<p>test</p>');
            $mailer->send($email);

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
                ->html('<h1>Just à Faire<small> - Nouvelle Recommandation</small></h1>');
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
        $isread = $recommandation->getIsRead();
        if($isread == 0) {
            $recommandation->setIsRead(1);
            $recommandation->setIsFirstView(1);
            $recommandation->setRecoState('inProgress');
            $this->getDoctrine()->getManager()->flush();

            return $this->render('gest_app/recommandation/show.html.twig', [
                'recommandation' => $recommandation,
            ]);
        }elseif($isread == 1){
            return $this->render('gest_app/recommandation/show2.html.twig', [
                'recommandation' => $recommandation,
            ]);
        }
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

    /**
     * @Route("/op_admin/gestapp/recommandation/compiluser", name="op_gestapp_recommandation_compiluser", methods={"GET"})
     */
    public function compilUser(): Response
    {
        $user = $this->getUser();
        $stats = $this->getDoctrine()->getRepository(Recommandation::class)->statsByUser($user);
        return $this->render('gest_app/recommandation/compiluser.html.twig', [
            'stats' => $stats,
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/reco/{id}", name="op_gestapp_recommandation_addrecoprice",methods={"GET","POST"})
     */
    public function addrecoprice(Recommandation $recommandation, Request $request): Response
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $description = $data['description'];
        $recoprice = $data['recoprice'];
        $recostate = $data['recostate'];
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) {return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        }elseif($recoprice and !$recostate){                            // enregistre seulement si l'estimation de la reco est entrée
            $recommandation->setRecoPrice($recoprice);
            $this->getDoctrine()->getManager()->flush();
            return $this->json([
                'code'=> 200,
                'message' => "L'estimation de la recommandation a été ajoutée à la recommandation."
            ], 200);
        }
        $recommandation->setName($name);
        $recommandation->setDescription($description);
        $recommandation->setRecoPrice($recoprice);
        $recommandation->setRecoState($recostate);
        $this->getDoctrine()->getManager()->flush();
        return $this->json([
            'code'=> 200,
            'message' => "L'estimation de la recommandation a été ajoutée à la recommandation."
        ], 200);
    }

    /**
     * Suppression d'une ligne index.php
     * @Route("/gest/app/recommandation/del/{id}", name="op_gestapp_recommandation_del", methods={"POST"})
     */
    public function DelEvent(Request $request, Recommandation $recommandation, SerializerInterface $serializer) : Response
    {
        $author = $recommandation->getAuthor();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($recommandation);
        $entityManager->flush();

        $RefreshRecommandation = $this->getDoctrine()->getRepository(Recommandation::class)->findByUser($author);

        return $this->json([
            'code'=> 200,
            'message' => "L'évènenemt a été supprimé",
            'liste' => $this->renderView('gest_app/recommandation/include/_liste.html.twig', [
                'recommandations' => $RefreshRecommandation
            ])
        ], 200);
    }
}
