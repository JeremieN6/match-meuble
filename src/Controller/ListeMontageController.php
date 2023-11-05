<?php

namespace App\Controller;

use App\Form\DemandeFormType;
use App\Form\OffreFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ListeMontageController extends AbstractController
{

    #[Route('/formulaire-offre-de-montage', name: 'app_offre_montage')]
    public function offreMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        //On crée le formulaire
        $offreMontageForm = $this->createForm(OffreFormType::class);

        //On traite la requête du formulaire
        $offreMontageForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($offreMontageForm->isSubmitted() && $offreMontageForm->isValid()){

             //envoie a l'entité
             $entityManager->persist($connectedUser);
             $entityManager->flush();
 
             $flashy->success('Ton offre de montage a été validé avec succès ! 🚀');
 
             //On redirige
             return $this->redirectToRoute('app_home');
        }

        return $this->render('listes/offreMontage.html.twig', [
            'controller_name' => 'ListeMontageController',
            'offreMontageForm' => $offreMontageForm->createView(),
            'user' => $connectedUser,
        ]);
    }

    #[Route('/formulaire-demande-de-montage', name: 'app_demande_montage')]
    public function demandeMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        //On crée le formulaire
        $demandeMontageForm = $this->createForm(DemandeFormType::class);

        //On traite la requête du formulaire
        $demandeMontageForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($demandeMontageForm->isSubmitted() && $demandeMontageForm->isValid()){

             //envoie a l'entité
             $entityManager->persist($connectedUser);
             $entityManager->flush();
 
             $flashy->success('Ta demande de montage a été validé avec succès ! 🚀');
 
             //On redirige
             return $this->redirectToRoute('app_home');
        }


        return $this->render('listes/demandeMontage.html.twig', [
            'controller_name' => 'ListeMontageController',
            'demandeMontageForm' => $demandeMontageForm->createView(),
            'user' => $connectedUser,
        ]);
    }

    #[Route('/liste-montage-meuble', name: 'app_offre_montage')]
    public function listeMontageMeuble(
        Request $request,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        return $this->render('listes/listeMontageMeuble.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
        ]);
    }
}