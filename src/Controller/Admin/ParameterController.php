<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Parameter;
use App\Form\Admin\ParameterType;
use App\Repository\Admin\ParameterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ParameterController extends AbstractController
{
    /**
     * @Route("/admin/parameter/", name="op_admin_parameter_index", methods={"GET"})
     */
    public function index(ParameterRepository $parameterRepository): RedirectResponse
    {
        $parameter = $this->getDoctrine()->getRepository(Parameter::class)->findFirstReccurence();
        if(!$parameter){
            return $this->redirectToRoute('op_admin_dashboard_first_install');
        }
        else {
            $idParameter = $parameter->getId();
            return $this->redirectToRoute('op_admin_parameter_edit',['id' => $idParameter]);
        }

    }

    /**
     * @Route("/admin/parameter/new", name="op_admin_parameter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $parameter = new Parameter();
        $form = $this->createForm(ParameterType::class, $parameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parameter);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_parameter_index');
        }

        return $this->render('admin/parameter/new.html.twig', [
            'parameter' => $parameter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/parameter/{id}", name="op_admin_parameter_show", methods={"GET"})
     */
    public function show(Parameter $parameter): Response
    {
        return $this->render('admin/parameter/show.html.twig', [
            'parameter' => $parameter,
        ]);
    }

    /**
     * @Route("/admin/parameter/{id}/edit", name="op_admin_parameter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parameter $parameter): Response
    {
        $form = $this->createForm(ParameterType::class, $parameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_parameter_index');
        }

        return $this->render('admin/parameter/edit.html.twig', [
            'parameter' => $parameter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/parameter/{id}", name="op_admin_parameter_delete", methods={"POST"})
     */
    public function delete(Request $request, Parameter $parameter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parameter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parameter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_admin_parameter_index');
    }
}
