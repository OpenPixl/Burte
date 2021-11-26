<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\Member2Type;
use App\Form\Admin\MemberType;
use App\Form\Admin\ClientType;
use App\Repository\Admin\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/admin/member/", name="op_admin_member_index", methods={"GET"})
     */
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findBy(array("type" => "producteur")),
        ]);
    }

    /**
     * @Route("/admin/member/vue", name="op_admin_member_vuefront", methods={"GET"})
     */
    public function vueFront(MemberRepository $memberRepository): Response
    {
        $members = $this->getDoctrine()->getRepository(Member::class)->vueFront();

        return $this->render('admin/member/index.html.twig', [
            'members' => $members,
        ]);
    }

    /**
     * @Route("/admin/member/new", name="op_admin_member_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $member->setPassword(
                $passwordEncoder->encodePassword(
                    $member,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            // partie de code pour envoyer un email au membre recommandé
            $email = (new Email())
                ->from('postmaster@openpixl.fr')
                ->to('xavier.burke@openpixl.fr')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('JUSTàFaire - Inscription')
                //->text('Sending emails is fun again!')
                ->html('
                    <h1>Just à Faire<small> - Nouvelle inscription</small></h1>
                    <hr>
                    <p>Une nouvelle inscription est enregistré dans l\'espace de membre.</p>
                    <p>Vous devez activer ce membre pour qu\'il puisse se connecter et publier des recommandations , ...</p>
                    ');
            $mailer->send($email);


            return $this->redirectToRoute('op_admin_member_index');
        }

        return $this->render('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/member/{id}", name="op_admin_member_show", methods={"GET"})
     */
    public function show(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @Route("/admin/member/{id}/edit", name="op_admin_member_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Member $member): Response
    {
        $password = $member->getPassword();
        $member->setPassword($password);
        $form = $this->createForm(Member2Type::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_member_index');
        }

        return $this->render('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/member/{id}/edit2", name="op_admin_member_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, Member $member): Response
    {
        $password = $member->getPassword();
        $member->setPassword($password);
        $form = $this->createForm(Member2Type::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_member_show', ['id' => $member->getId()]);
        }

        return $this->render('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/op_admin/member/{id}", name="admin_member_delete", methods={"POST"})
     */
    public function delete(Request $request, Member $member): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_admin_member_index');
    }

    /**
     * @Route("/webapp/members", name="op_admin_member_front_members", methods={"GET"})
     */
    public function Members(MemberRepository $memberRepository)
    {
        return $this->render('admin/member/members.html.twig', [
            'members' => $memberRepository->listMembersOnFront(),
        ]);
    }

    /**
     * @Route("/webapp/member/{id}", name="op_admin_member_front_member", methods={"GET"})
     */
    public function Member(MemberRepository $memberRepository, $id)
    {

        return $this->render('admin/member/member.html.twig', [
            'member' => $memberRepository->MemberOnFront($id),
        ]);
    }

    /**
     * Permet de mettre en menu la poge ou non
     * @Route("/op_admin/member/verified/{id}", name="op_admin_member_verified")
     */
    public function jsMenu(Member $member, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $isVerified = $member->getIsVerified();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isVerified == true){
            $member->setIsVerified(0);
            $em->flush();
            return $this->json(['code'=> 200, 'message' => "L'utilisateur n'accède plus à l'administration"], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $member->setIsVerified(1);
        $em->flush();
        return $this->json(['code'=> 200, 'message' => "L'utilisateur accède à l'administration"], 200);
    }

    /**
     * @Route("/admin/member/del/{id}", name="op_admin_member_del", methods={"POST"})
     */
    public function Del(Request $request, Member $member) : Response
    {
        $Structure = $member->getStructure();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($member);
        $entityManager->remove($Structure);
        $entityManager->flush();

        return $this->json([
            'code'=> 200,
            'message' => "Le membre a été supprimé"
        ], 200);
    }
}
