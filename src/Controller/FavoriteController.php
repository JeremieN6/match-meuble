<?php

namespace App\Controller;

use App\Entity\FavoriteOffre;
use App\Entity\FavoriteDemande;
use App\Entity\OffreDeTravail;
use App\Entity\DemandeDeTravail;
use App\Repository\FavoriteOffreRepository;
use App\Repository\FavoriteDemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    #[Route('/favoris/offre/{id}', name: 'toggle_favorite_offre', methods: ['POST'])]
    public function toggleOffre(OffreDeTravail $offre, FavoriteOffreRepository $repo, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $token = $request->request->get('_token') ?? $request->headers->get('X-CSRF-TOKEN');
        if (!$this->isCsrfTokenValid('fav_offre'.$offre->getId(), $token)) {
            return $this->json(['error' => 'Invalid CSRF token'], 400);
        }
        $user = $this->getUser();
        $existing = $repo->findOneBy(['user' => $user, 'offre' => $offre]);
        if ($existing) {
            $em->remove($existing);
            $em->flush();
            return $this->json(['favori' => false]);
        }
        $fav = (new FavoriteOffre())->setUser($user)->setOffre($offre);
        $em->persist($fav);
        $em->flush();
        return $this->json(['favori' => true]);
    }

    #[Route('/favoris/demande/{id}', name: 'toggle_favorite_demande', methods: ['POST'])]
    public function toggleDemande(DemandeDeTravail $demande, FavoriteDemandeRepository $repo, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $token = $request->request->get('_token') ?? $request->headers->get('X-CSRF-TOKEN');
        if (!$this->isCsrfTokenValid('fav_demande'.$demande->getId(), $token)) {
            return $this->json(['error' => 'Invalid CSRF token'], 400);
        }
        $user = $this->getUser();
        $existing = $repo->findOneBy(['user' => $user, 'demande' => $demande]);
        if ($existing) {
            $em->remove($existing);
            $em->flush();
            return $this->json(['favori' => false]);
        }
        $fav = (new FavoriteDemande())->setUser($user)->setDemande($demande);
        $em->persist($fav);
        $em->flush();
        return $this->json(['favori' => true]);
    }

    #[Route('/mes-favoris', name: 'app_favorites', methods: ['GET'])]
    public function list(FavoriteOffreRepository $favOffres, FavoriteDemandeRepository $favDemandes): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $offres = $favOffres->findBy(['user' => $user], ['createdAt' => 'DESC']);
        $demandes = $favDemandes->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('userAccount/favoris.html.twig', [
            'favOffres' => $offres,
            'favDemandes' => $demandes,
        ]);
    }
}
