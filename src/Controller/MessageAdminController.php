<?php

namespace App\Controller;

use App\Entity\MessageAdmin;
use App\Form\MessageAdminType;
use App\Repository\MessageAdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message/admin")
 */
class MessageAdminController extends AbstractController
{
    /**
     * @Route("/", name="message_admin_index", methods={"GET"})
     */
    public function index(MessageAdminRepository $messageAdminRepository): Response
    {
        return $this->render('message_admin/index.html.twig', [
            'message_admins' => $messageAdminRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="message_admin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $messageAdmin = new MessageAdmin();
        $form = $this->createForm(MessageAdminType::class, $messageAdmin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($messageAdmin);
            $entityManager->flush();
            echo '<script>alert("votre Message a bien été transmis, nous vous contacterons bientôt")</script>';
            return $this->redirectToRoute('message_admin_new');
        }

        return $this->render('message_admin/new.html.twig', [
            'message_admin' => $messageAdmin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_admin_show", methods={"GET"})
     */
    public function show(MessageAdmin $messageAdmin): Response
    {
        return $this->render('message_admin/show.html.twig', [
            'message_admin' => $messageAdmin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MessageAdmin $messageAdmin): Response
    {
        $form = $this->createForm(MessageAdminType::class, $messageAdmin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_admin_index');
        }

        return $this->render('message_admin/edit.html.twig', [
            'message_admin' => $messageAdmin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_admin_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MessageAdmin $messageAdmin): Response
    {
        if ($this->isCsrfTokenValid('delete'.$messageAdmin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($messageAdmin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_admin_index');
    }
}
