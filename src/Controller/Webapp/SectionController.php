<?php

namespace App\Controller\Webapp;

use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
use App\Form\Webapp\SectionType;
use App\Form\Webapp\Section2Type;
use App\Repository\Webapp\SectionRepository;
use CKSource\CKFinder\Response\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class SectionController extends AbstractController
{
    /**
     * @Route("/webapp/section/", name="op_webapp_section_index", methods={"GET"})
     */
    public function index(SectionRepository $sectionRepository): Response
    {
        return $this->render('webapp/section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/webapp/section/bypage/{page}", name="op_webapp_section_bypage", methods={"GET"})
     */
    public function byPage(SectionRepository $sectionRepository, $page): Response
    {
        $element = $this->getDoctrine()->getRepository(Page::class)->find($page);

        return $this->render('webapp/section/bypage.html.twig', [
            'sections' => $sectionRepository->findbypage($page),
            'element' => $element,
            'page' => $page
        ]);
    }

    /**
     * @Route("/webapp/section/frontbypage/{page}", name="op_webapp_section_frontbypage", methods={"GET"})
     */
    public function frontByPage($page): Response
    {
        $sections = $this->getDoctrine()->getRepository(Section::class)->findbypage($page);

        return $this->render('webapp/section/frontbypage.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/webapp/section/new", name="op_webapp_section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_section_index');
        }

        return $this->render('webapp/section/new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/section/newbypage/{page}", name="op_webapp_section_newbypage", methods={"GET","POST"})
     */
    public function newbypage(Request $request, $page): Response
    {

        $section = new Section();

        $form = $this->createForm(Section2Type::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_section_index');
        }

        return $this->render('webapp/section/new2.html.twig', [
            'section' => $section,
            'page'=> $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/section/addsection/{idpage}", name="op_webapp_section_add", methods={"GET","POST"})
     */
    public function addSection(Request $request, $idpage) : Response
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->find($idpage);

        $element = json_decode($request->getContent(), true);
        $section = new Section;
        $section->setTitle($element['title']);
        $section->setDescription($element['description']);
        $section->setAttrId($element['attrId']);
        $section->setAttrName($element['attrName']);
        $section->setAttrClass($element['attrClass']);
        $section->setPage($page);
        return $this->json([
            'code'          => 200,
            'message'       => 'accès au json'
        ], 200);
    }

    /**
     * @Route("/webapp/section/{id}", name="op_webapp_section_show", methods={"GET"})
     */
    public function show(Section $section): Response
    {
        return $this->render('webapp/section/show.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * @Route("/webapp/section/{id}/edit", name="op_webapp_section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Section $section): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_section_index');
        }

        return $this->render('webapp/section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/section/{id}", name="op_webapp_section_delete", methods={"POST"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_webapp_section_index');
    }
}
