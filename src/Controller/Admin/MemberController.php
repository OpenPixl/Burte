<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\Member2Type;
use App\Form\Admin\MemberType;
use App\Repository\Admin\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/member")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="op_admin_member_index", methods={"GET"})
     */
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="op_admin_member_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_member_index');
        }

        return $this->render('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="op_admin_member_show", methods={"GET"})
     */
    public function show(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="op_admin_member_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Member $member): Response
    {
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
     * @Route("/{id}", name="admin_member_delete", methods={"POST"})
     */
    public function delete(Request $request, Member $member): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_member_index');
    }
}
