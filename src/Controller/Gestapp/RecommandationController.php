<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Recommandation;
use App\Form\Gestapp\RecommandationType;
use App\Repository\Gestapp\RecommandationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
        $receipts = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserReceiptRead($user);
        $sends = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserSendRead($user);
        return $this->render('gestapp/recommandation/index.html.twig', [
            'receipts' => $receipts,
            'sends' => $sends
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/reload", name="op_gestapp_recommandation_indexreload", methods={"GET"})
     */
    public function indexReload(RecommandationRepository $recommandationRepository): Response
    {
        $user = $this->getUser();
        $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUser($user);
        return $this->render('gestapp/recommandation/reload.html.twig', [
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
        $recommandation->setIsFirstView(1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recommandation);
            $entityManager->flush();

            //récupération de l'adresss mail pour le membre recommandé
            $membre = $recommandation->getMember();
            $email = $membre->getEmail();

            // partie de code pour envoyer un email au membre recommandé
            $email = (new TemplatedEmail())
                ->from('postmaster@openpixl.fr')
                ->to($email)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('JUSTàFaire - une nouvelle recommandation vous attends dans votre espace')
                //->text('Sending emails is fun again!')
                ->htmlTemplate('email/newRecommandation.html.twig')
                ->context([
                    'author' => $user->getUsername(),
                ]);
            $mailer->send($email);

            // partie de code pour envoyer un email au membre recommandé
            $email = (new TemplatedEmail())
                ->from('postmaster@openpixl.fr')
                ->to('xavier.burke@openpixl.fr')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('JUSTàFaire - une nouvelle recommandation a été émise depuis le site')
                //->text('Sending emails is fun again!')
                ->htmlTemplate('email/newRecommandationWebMaster.html.twig');
            $mailer->send($email);

            return $this->redirectToRoute('op_gestapp_recommandation_index');
        }

        return $this->render('gestapp/recommandation/new.html.twig', [
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
            $recommandation->setIsRead(0);
            $recommandation->setIsFirstView(1);
            $recommandation->setRecoState('InProgress');
            $this->getDoctrine()->getManager()->flush();

            return $this->render('gestapp/recommandation/show.html.twig', [
                'recommandation' => $recommandation,
            ]);
        }elseif($isread == 1){
            return $this->render('gestapp/recommandation/show2.html.twig', [
                'recommandation' => $recommandation,
            ]);
        }
        return $this->render('gestapp/recommandation/show.html.twig', [
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

            return $this->redirectToRoute('gestapp_recommandation_index');
        }

        return $this->render('gestapp/recommandation/edit.html.twig', [
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
     * @Route("/op_admin/gestapp/recommandation/byuserReceipt/{hide}", name="op_gestapp_recommandation_byuserreceipt", methods={"GET"})
     */
    public function byUserReceipt($hide, RecommandationRepository $recommandationRepository): Response
    {
        $user = $this->getUser();
        $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserReceipt($user);
        return $this->render('gestapp/recommandation/byuserReceipt.html.twig', [
            'recommandations' => $recommandations,
            'hide' => $hide
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/byuserSend/{hide}", name="op_gestapp_recommandation_byusersend", methods={"GET"})
     */
    public function byUserSend($hide,RecommandationRepository $recommandationRepository): Response
    {
        $user = $this->getUser();
        $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserSend($user);
        $hide = 1;
        return $this->render('gestapp/recommandation/byuserSend.html.twig', [
            'recommandations' => $recommandations,
            'hide' => $hide
        ]);
    }

    /**
     * @Route("/op_admin/gestapp/recommandation/compiluser", name="op_gestapp_recommandation_compiluser", methods={"GET"})
     */
    public function compilUser(): Response
    {
        $user = $this->getUser();
        $stats = $this->getDoctrine()->getRepository(Recommandation::class)->statsByUser($user);
        return $this->render('gestapp/recommandation/compiluser.html.twig', [
            'stats' => $stats,
        ]);
    }

    /**
     * Suppression d'une ligne index.php
     * @Route("/op_admin/gestapp/recommandation/del/{id}", name="op_gestapp_recommandation_suppr", methods={"POST"})
     */
    public function DelEvent(Request $request, Recommandation $recommandation) : Response
    {
        $author = $recommandation->getAuthor();
        $hide = 0;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($recommandation);
        $entityManager->flush();

        $receipt = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserReceipt($author);

        return $this->json([
            'code'=> 200,
            'message' => "L'évènenemt a été supprimé",
            'listeReceipt' => $this->renderView('gestapp/recommandation/include/_liste.html.twig', [
                'recommandations' => $receipt,
                'hide' => $hide
            ]),
        ], 200);
    }

    /**
     * Mise a jour des recommandations depuis le show Recommandation
     * @Route("/op_admin/gestapp/recommandation/reco/{id}", name="op_gestapp_recommandation_addrecoprice",methods={"GET","POST"})
     */
    public function addrecoprice(Recommandation $recommandation, Request $request): Response
    {
        $user = $this->getUser();
        $hide = 0;
        $isread = $recommandation->getIsRead();

        if(!$user) {return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        }elseif($isread == 0){
            $data = json_decode($request->getContent(), true);
            $recoprice = $data['recoprice'];

            // Hydratation de l'entité
            $recommandation->setIsRead('1');
            $recommandation->setRecoPrice($recoprice);
            $this->getDoctrine()->getManager()->flush();

            // Préparation de la liste à rafraichir
            $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserReceipt($user);

            // Réponse JSON
            return $this->json([
                'code'=> 200,
                'message' => "L'estimation de la recommandation a été ajoutée à la recommandation.",
                'listeReceipt' => $this->renderView('gestapp/recommandation/include/_liste.html.twig', [
                    'recommandations' => $recommandations,
                    'hide' => $hide
                ]),
            ], 200);
        }else{
            $data = json_decode($request->getContent(), true);
            $name = $data['name'];
            $description = $data['description'];
            $recoprice = $data['recoprice'];
            $recostate = $data['recostate'];
            $clientAddress = $data['clientAddress'];
            $clientComplement = $data['clientComplement'];
            $clientZipcode = $data['clientZipcode'];
            $clientCity = $data['clientCity'];

            // Hydratation de l'entité
            $recommandation->setRecoPrice($recoprice);
            $recommandation->setName($name);
            $recommandation->setRecoState($recostate);
            $recommandation->setDescription($description);
            $recommandation->setClientAddress($clientAddress);
            $recommandation->setClientCity($clientComplement);
            $recommandation->setClientZipcode($clientZipcode);
            $recommandation->setClientCity($clientCity);
            $this->getDoctrine()->getManager()->flush();

            // Préparation de la liste à rafraichir
            $recommandations = $this->getDoctrine()->getRepository(Recommandation::class)->findByUserReceipt($user);

            // Réponse JSON
            return $this->json([
                'code'=> 200,
                'message' => "L'estimation de la recommandation a été ajoutée à la recommandation.",
                'listeReceipt' => $this->renderView('gestapp/recommandation/include/_liste.html.twig', [
                    'recommandations' => $recommandations,
                    'hide' => $hide
                ]),
            ], 200);
        }
    }
}
