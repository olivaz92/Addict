<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Entity\User;
use App\Form\ActualiteType;
use App\Form\CommentaireType;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actualite")
 */
class ActualiteController extends AbstractController
{

    /**
     * @Route("/", name="actualite_index", methods={"GET","POST"})
     * @param Request $request
     * @param ActualiteRepository $actualiteRepository
     * @param $titre
     * @return Response
     */
    public function index(Request $request,ActualiteRepository $actualiteRepository): Response
    {
        $user= $this->getUser();
        $actualite = new Actualite();
        $commentaire= new Commentaire();
        //Formulaires des actualites
        $formActualite = $this->createForm(ActualiteType::class, $actualite);
        $formActualite->handleRequest($request);
        //Formulaire commentaire
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);
        //On vérifie le formulaire d'actualité
        if ($formActualite->isSubmitted() && $formActualite->isValid())
        {
            /** @var UploadedFile $photo */
            $photo = $formActualite['image']->getData();

            if ($photo)
            {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                try
                {
                    $photo->move($this->getParameter('image_directory'), $newFilename);
                }

                catch (FileException $e)
                {

                }
                $actualite->setImage($newFilename);

            }
            $actualite->setIdUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();
        }
        //vérifie formulaire des commentaires
        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {

            $commentaire->setIdUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

        }

             return $this->render('actualite/index.html.twig', [
            'actualites' => $actualiteRepository->getCustomActualite(),
            'formActualite' => $formActualite->createView(),
            'formCommentaire' => $formCommentaire->createView(),

        ]);
    }

    /**
     * @Route("/new", name="actualite_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $actualite = new Actualite();
        $user= $this->getUser();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photo */
            $photo = $form['image']->getData();

            if ($photo)
            {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                try
                {
                    $photo->move($this->getParameter('image_directory'), $newFilename);
                }

                catch (FileException $e)
                {

                }
                $actualite->setImage($newFilename);

            }

            $actualite->setIdUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();
            return $this->redirectToRoute('actualite_index');
        }

        return $this->render('actualite/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actualite_show", methods={"GET","POST"})
     * @param Actualite $actualite
     * @param Request $request
     * @param ActualiteRepository $actualiteRepository
     * @return Response
     */
        public function show(Actualite $actualite,Request $request,ActualiteRepository $actualiteRepository): Response
        {
            if (!$actualite) {
                throw $this->createNotFoundException('Aucun article trouvé');

            }
            $user= $this->getUser();
            $newCommentaire = new Commentaire();
            $form = $this->createForm(CommentaireType::class, $newCommentaire);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
//                /** @var UploadedFile $photo */
//                $photo = $form['image']->getData();
//
//                if ($photo)
//                {
//                    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
//
//                    //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo->guessExtension();
//
//                    try
//                    {
//                        $photo->move($this->getParameter('image_directory'), $newFilename);
//                    }
//
//                    catch (FileException $e)
//                    {
//
//                    }
//                    $newCommentaire->setImage($newFilename);
//
//                }
                $newCommentaire->setIdUser($user);
                $newCommentaire->setIdActualite($actualite);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newCommentaire);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('info', 'Commentaire bien ajouté');
            }

            return $this->render('actualite/show.html.twig', [
                'actualite' => $actualite,
                'form' => $form->createView(),

            ]);
        }

    /**
     * @Route("/{id}/edit", name="actualite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actualite $actualite): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photo */
            $photo = $form['image']->getData();

            if ($photo)
            {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                try
                {
                    $photo->move($this->getParameter('image_directory'), $newFilename);
                }

                catch (FileException $e)
                {

                }
                $actualite->setImage($newFilename);

            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();
            return $this->redirectToRoute('actualite_index');
        }

        return $this->render('actualite/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actualite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Actualite $actualite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actualite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actualite_index');
    }
}
