<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Entity\Admin\Structure;
use App\Form\Admin\StructureType;
use App\Repository\Admin\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class StructureController extends AbstractController
{
    /**
     * @Route("/admin/structure/", name="op_admin_structure_index", methods={"GET"})
     */
    public function index(StructureRepository $structureRepository): Response
    {
        return $this->render('admin/structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/structure/find/{iduser}", name="op_admin_structure_find", methods={"GET"})
     */
    public function findmystructure(StructureRepository $structureRepository, $iduser): Response
    {
        $member = $this->getDoctrine()->getRepository(Member::class)->find($iduser);
        $Structure = $member->getStructure();
        $idStructure = $Structure->getId();
        if(!$idStructure){
            return $this->redirectToRoute('op_admin_structure_new2', ['idmembre' => $member->getId()]);
        }
        return $this->redirectToRoute('op_admin_structure_edit', ['id' => $idStructure]);
    }

    /**
     * @Route("/admin/structure/new/{idmembre}", name="op_admin_structure_new", methods={"GET","POST"})
     */
    public function new(Request $request, $idmembre): Response
    {
        $membre = $this->getDoctrine()->getRepository(Member::class)->find($idmembre);
        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($structure);
            $entityManager->flush();

            $membre->setStructure($structure);

            return $this->redirectToRoute('op_admin_dashboard_index');
        }

        return $this->render('admin/structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/structure/addstruct/{idmembre}", name="op_admin_structure_addstruct", methods={"GET","POST"})
     */
    public function addstruct(Request $request, $idmembre): Response
    {
        $membre = $this->getDoctrine()->getRepository(Member::class)->find($idmembre);
        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($structure);
            $entityManager->flush();

            $membre->setStructure($structure);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_member_index');
        }

        return $this->render('admin/structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/structure/new2", name="op_admin_structure_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $idmembre = $this->getUser()->getId();

        $membre = $this->getDoctrine()->getRepository(Member::class)->find($idmembre);
        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($structure);
            $entityManager->flush();

            $membre->setStructure($structure);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_structure_index');
        }

        return $this->render('admin/structure/new2.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/structure/{id}", name="op_admin_structure_show", methods={"GET"})
     */
    public function show(Structure $structure): Response
    {
        return $this->render('admin/structure/show.html.twig', [
            'structure' => $structure,
        ]);
    }

    /**
     * @Route("/admin/structure/{id}/edit", name="op_admin_structure_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Structure $structure): Response
    {
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_dashboard_index');
        }

        return $this->render('admin/structure/edit.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/structure/{id}/edit2", name="op_admin_structure_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, Structure $structure): Response
    {
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_structure_edit2', ['id'=> $structure->getId()]);
        }

        return $this->render('admin/structure/edit2.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/structure/{id}", name="op_admin_structure_delete", methods={"POST"})
     */
    public function delete(Request $request, Structure $structure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$structure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($structure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_structure_index');
    }
}
