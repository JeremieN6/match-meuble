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
    private function buildOffreCards(array $offres): array
    {
        $cards = [];
        foreach ($offres as $offre) {
            $title = $offre->getTitre() ?: 'Offre de montage';
            $desc = $offre->getDescription() ?: 'Offre sans description fournie.';
            if (mb_strlen($desc) > 160) {
                $desc = mb_substr($desc, 0, 157) . '…';
            }
            $cards[] = [
                'title' => $title,
                'description' => $desc,
                'image' => 'https://images.pexels.com/photos/5582598/pexels-photo-5582598.jpeg?auto=compress&cs=tinysrgb&w=600',
                'href' => '#',
                'cta' => 'Voir l\'offre',
            ];
        }

        

        return $cards;
    }

    private function buildDemandeCards(array $demandes): array
    {
        $cards = [];
        foreach ($demandes as $demande) {
            $title = $demande->getTitre() ?: 'Demande de montage';
            $desc = $demande->getDescription() ?: 'Demande sans description fournie.';
            if (mb_strlen($desc) > 160) {
                $desc = mb_substr($desc, 0, 157) . '…';
            }
            $cards[] = [
                'title' => $title,
                'description' => $desc,
                'image' => 'https://images.pexels.com/photos/4247947/pexels-photo-4247947.jpeg?auto=compress&cs=tinysrgb&w=600',
                'href' => '#',
                'cta' => 'Voir la demande',
            ];
        }

        

        return $cards;
    }

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
            $status = $offre->getStatus();
            $statusId = $status ? $status->getId() : null;
            $statusName = $statusId ? $statusOffreRepository->find($statusId)->getNomStatus() : 'Statut inconnu';
            $statusNames[$statusId] = $statusName;
        }

        $offreCards = $this->buildOffreCards($listeOffreTravail);

        return $this->render('liste/liste_offre.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
            'listeOffreTravail' => $listeOffreTravail,
            'listeDemandeTravail' => $listeDemandeTravail,
            'statusNames' => $statusNames,
            'offreCards' => $offreCards,
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

        $demandeCards = $this->buildDemandeCards($listeDemandeTravail);

        return $this->render('liste/liste_demande.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
            'listeOffreTravail' => $listeOffreTravail,
            'listeDemandeTravail' => $listeDemandeTravail,
            'demandeCards' => $demandeCards,
        ]);
    }
}