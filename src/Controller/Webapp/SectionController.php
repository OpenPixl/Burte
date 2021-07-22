<?php

namespace App\Controller\Webapp;

use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
use App\Form\Webapp\SectionType;
use App\Form\Webapp\Section2Type;
use App\Repository\Webapp\SectionRepository;
use CKSource\CKFinder\Response\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
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
        $sections = $this->getDoctrine()->getRepository(Section::class)->findWithPage();

        return $this->render('webapp/section/index.html.twig', [
            'sections' => $sections,
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
        ]);
    }

    /**
     * @Route("/webapp/section/frontbypage/{id}", name="op_webapp_section_frontbypage", methods={"GET"})
     */
    public function frontByPage(Page $page): Response
    {
        $sections = $this->getDoctrine()->getRepository(Section::class)->findBy(array('page' => $page), array('position' => 'ASC'));

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

    /**
     * Permet d'activer ou de désactiver la mise en vedette d'une section sur la page d'accueil
     * @Route("/webapp/section/jsstar/{id}/json", name="op_webapp_section_star")
     */
    public function jsstar(Section $section, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $isstar = $section->getFavorites();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isstar == true){
            $section->setfavorites(0);
            $em->flush();
            return $this->json([
                'code'      => 200,
                'message'   => "La section n'est plus publiée sur la page d'acccueil."
            ], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $section->setFavorites(1);
        $em->flush();
        return $this->json([
            'code'          => 200,
            'message'       => "La section est publiée sur la page d'acccueil."
        ], 200);
    }

    /**
     * Supprimer la section
     * @Route("/webapp/section/jsdel/{id}", name="op_webapp_section_del")
     */
    public function jsdel(Section $section, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // code de suppression
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($section);
        $entityManager->flush();
        $em->flush();
        return $this->json([
            'code'          => 200,
            'message'       => "La section est correctement supprimée."
        ], 200);
    }

    /**
     * @Route("/webapp/section/del/{id}", name="op_webapp_section_del", methods={"POST"})
     */
    public function DelEvent(Request $request, Section $section) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($section);
        $entityManager->flush();

        return $this->json([
            'code'=> 200,
            'message' => "L'évènenemt a été supprimé"
        ], 200);
    }

}
