<?php

namespace App\Controller;

use App\Entity\Pense;
use App\Form\PenseType;
use App\Repository\PenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pense")
 */
class PenseController extends AbstractController
{
    /**
     * @Route("/", name="pense_index", methods={"GET"})
     */
    public function index(PenseRepository $penseRepository): Response
    {
        return $this->render('pense/index.html.twig', [
            'penses' => $penseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user= $this->getUser();
        $pense = new Pense();
        $form = $this->createForm(PenseType::class, $pense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pense->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pense);
            $entityManager->flush();

            return $this->redirectToRoute('pense_index');
        }

        return $this->render('pense/new.html.twig', [
            'pense' => $pense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pense_show", methods={"GET"})
     */
    public function show(Pense $pense): Response
    {
        return $this->render('pense/show.html.twig', [
            'pense' => $pense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pense $pense): Response
    {
        $form = $this->createForm(PenseType::class, $pense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pense_index');
        }

        return $this->render('pense/edit.html.twig', [
            'pense' => $pense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pense $pense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pense_index');
    }
}
