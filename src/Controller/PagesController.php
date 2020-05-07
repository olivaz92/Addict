<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Entity\Pense;
use App\Entity\User;
use App\Form\ActualiteType;
use App\Form\CommentaireType;
use App\Form\PenseType;
use App\Form\RegistrationFormType;
use App\Repository\ActualiteRepository;
use App\Repository\PenseRepository;
use App\Security\LoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     * @param Request $request
     * @param ActualiteRepository $actualiteRepository
     * @param PenseRepository $penseRepository
     * @return Response
     */

    public function accueil(Request $request,ActualiteRepository $actualiteRepository,PenseRepository $penseRepository): Response
    {
        $user= $this->getUser();
        $actualite = new Actualite();
        $commentaire= new Commentaire();
        $pense= new Pense();
        //Formulaires des actualites
        $formActualite = $this->createForm(ActualiteType::class, $actualite);
        $formActualite->handleRequest($request);
        //Formulaires des Pensées
        $formPense = $this->createForm(PenseType::class, $pense);
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
        //Vérifie formulaire des pensés
        if ($formPense->isSubmitted() && $formPense->isValid()) {
            $pense->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pense);
            $entityManager->flush();
            echo '<script>alert("Votre pensée sera prévisualiée")</script>';

//            return $this->redirectToRoute('accueil');
        }

        return $this->render('pages/accueil.html.twig', [
            'actualites' => $actualiteRepository->getCustomActualite(),
            'penses' => $penseRepository->findAll(),
            'formActualite' => $formActualite->createView(),
            'formCommentaire' => $formCommentaire->createView(),
        ]);
    }

    /**
     * @Route("/new", name="formulaire_pense", methods={"GET","POST"})
     */
    public function formulaire_pense(Request $request): Response
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

        return $this->render('pages/pense_form.html.twig', [
            'pense' => $pense,
            'form' => $form->createView(),
        ]);
    }


}
