<?php

namespace App\Controller;

use App\Repository\DemandeDeTravailRepository;
use App\Repository\OffreDeTravailRepository;
use App\Repository\StatusOffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ListeMontageController extends AbstractController
{

    #[Route('/liste-offre-montage', name: 'app_liste_offre_montage')]
    public function listeOffreMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        OffreDeTravailRepository $offreDeTravailRepository,
        DemandeDeTravailRepository $demandeDeTravailRepository,
        StatusOffreRepository $statusOffreRepository,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        //Récupérer toutes les offres de demandes montage dans l'entité correspondante
        $listeOffreTravail = $offreDeTravailRepository->findAll();
        $listeDemandeTravail = $demandeDeTravailRepository->findAll();

        // Récupérer le nom du statut pour chaque offre
        $statusNames = [];
        foreach ($listeOffreTravail as $offre) {
            $statusId = $offre->getStatus()->getId();
            $statusName = $statusOffreRepository->find($statusId)->getNomStatus();
            $statusNames[$statusId] = $statusName;
        }

        return $this->render('liste/liste_offre.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
            'listeOffreTravail' => $listeOffreTravail,
            'listeDemandeTravail' => $listeDemandeTravail,
            'statusNames' => $statusNames,
        ]);
    }

    #[Route('/liste-demande-montage', name: 'app_liste_demande_montage')]
    public function listeDemandeMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        OffreDeTravailRepository $offreDeTravailRepository,
        DemandeDeTravailRepository $demandeDeTravailRepository,
        StatusOffreRepository $statusOffreRepository,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        //Récupérer toutes les offres et demandes de montage dans l'entité correspondante
        $listeOffreTravail = $offreDeTravailRepository->findAll();
        $listeDemandeTravail = $demandeDeTravailRepository->findAll();


        return $this->render('liste/liste_demande.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
            'listeOffreTravail' => $listeOffreTravail,
            'listeDemandeTravail' => $listeDemandeTravail
        ]);
    }
}