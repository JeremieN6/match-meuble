<?php

namespace App\Controller;

use App\Entity\DemandeDeTravail;
use App\Entity\OffreDeTravail;
use App\Repository\DemandeDeTravailRepository;
use App\Repository\OffreDeTravailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(
        OffreDeTravailRepository $offres,
        DemandeDeTravailRepository $demandes
    ): Response {
        $offreCards = [];
        foreach ($offres->findAll() as $offre) {
            $desc = $offre->getDescription() ?: '';
            if (mb_strlen($desc) > 160) {
                $desc = mb_substr($desc, 0, 157) . '…';
            }
            $offreCards[] = [
                'type' => 'offre',
                'title' => $offre->getTitre() ?: 'Offre de montage',
                'description' => $desc ?: 'Offre sans description fournie.',
                'image' => 'https://images.pexels.com/photos/5582598/pexels-photo-5582598.jpeg?auto=compress&cs=tinysrgb&w=600',
                'href' => $this->generateUrl('app_annonces_offre_show', ['id' => $offre->getId()]),
                'cta' => "Voir l'offre",
                'localisation' => $offre->getLocalisation(),
            ];
        }

        $demandeCards = [];
        foreach ($demandes->findAll() as $demande) {
            $desc = $demande->getDescription() ?: '';
            if (mb_strlen($desc) > 160) {
                $desc = mb_substr($desc, 0, 157) . '…';
            }
            $demandeCards[] = [
                'type' => 'demande',
                'title' => $demande->getTitre() ?: 'Demande de montage',
                'description' => $desc ?: 'Demande sans description fournie.',
                'image' => 'https://images.pexels.com/photos/4247947/pexels-photo-4247947.jpeg?auto=compress&cs=tinysrgb&w=600',
                'href' => $this->generateUrl('app_annonces_demande_show', ['id' => $demande->getId()]),
                'cta' => 'Voir la demande',
                'localisation' => $demande->getZoneAction(),
            ];
        }

        $cards = array_merge($offreCards, $demandeCards);

        return $this->render('annonces/index.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/annonces/offre/{id}', name: 'app_annonces_offre_show', requirements: ['id' => '\\d+'])]
    public function showOffre(OffreDeTravail $offre): Response
    {
        return $this->render('annonces/show_offre.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/annonces/demande/{id}', name: 'app_annonces_demande_show', requirements: ['id' => '\\d+'])]
    public function showDemande(DemandeDeTravail $demande): Response
    {
        return $this->render('annonces/show_demande.html.twig', [
            'demande' => $demande,
        ]);
    }
}
