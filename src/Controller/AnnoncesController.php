<?php

namespace App\Controller;

use App\Entity\DemandeDeTravail;
use App\Entity\OffreDeTravail;
use App\Repository\DemandeDeTravailRepository;
use App\Repository\OffreDeTravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(
        OffreDeTravailRepository $offres,
        DemandeDeTravailRepository $demandes,
        EntityManagerInterface $em
    ): Response {
        $offreCards = [];
        foreach ($offres->findAll() as $offre) {
            if (!$offre->getSlug() && $offre->getTitre()) { $offre->setSlug($this->slugify($offre->getTitre())); $em->persist($offre); }
            $desc = $offre->getDescription() ?: '';
            if (mb_strlen($desc) > 160) { $desc = mb_substr($desc, 0, 157) . '…'; }
            $offreCards[] = [
                'id' => $offre->getId(),
                'type' => 'offre',
                'title' => $offre->getTitre() ?: 'Offre de montage',
                'description' => $desc ?: 'Offre sans description fournie.',
                'image' => 'https://images.pexels.com/photos/5582598/pexels-photo-5582598.jpeg?auto=compress&cs=tinysrgb&w=600',
                'href' => $this->generateUrl('app_annonces_offre_show', ['id' => $offre->getId(), 'slug' => $offre->getSlug() ?: $this->slugify($offre->getTitre() ?? 'offre')]),
                'cta' => "Voir l'offre",
                'localisation' => $offre->getLocalisation(),
                'favUrl' => $this->generateUrl('toggle_favorite_offre', ['id' => $offre->getId()]),
                'favToken' => $this->container->get('security.csrf.token_manager')->getToken('fav_offre'.$offre->getId())->getValue(),
            ];
        }

        $demandeCards = [];
        foreach ($demandes->findAll() as $demande) {
            if (!$demande->getSlug() && $demande->getTitre()) { $demande->setSlug($this->slugify($demande->getTitre())); $em->persist($demande); }
            $desc = $demande->getDescription() ?: '';
            if (mb_strlen($desc) > 160) { $desc = mb_substr($desc, 0, 157) . '…'; }
            $demandeCards[] = [
                'id' => $demande->getId(),
                'type' => 'demande',
                'title' => $demande->getTitre() ?: 'Demande de montage',
                'description' => $desc ?: 'Demande sans description fournie.',
                'image' => 'https://images.pexels.com/photos/4247947/pexels-photo-4247947.jpeg?auto=compress&cs=tinysrgb&w=600',
                'href' => $this->generateUrl('app_annonces_demande_show', ['id' => $demande->getId(), 'slug' => $demande->getSlug() ?: $this->slugify($demande->getTitre() ?? 'demande')]),
                'cta' => 'Voir la demande',
                'localisation' => $demande->getZoneAction(),
                'favUrl' => $this->generateUrl('toggle_favorite_demande', ['id' => $demande->getId()]),
                'favToken' => $this->container->get('security.csrf.token_manager')->getToken('fav_demande'.$demande->getId())->getValue(),
            ];
        }

        $em->flush();
        $cards = array_merge($offreCards, $demandeCards);

        return $this->render('annonces/index.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/annonces/offre/{id}-{slug}', name: 'app_annonces_offre_show', requirements: ['id' => '\\d+'])]
    public function showOffre(OffreDeTravail $offre, string $slug, OffreDeTravailRepository $offreRepo): Response
    {
        $canonical = $offre->getSlug() ?: $this->slugify($offre->getTitre() ?? 'offre');
        if ($slug !== $canonical) {
            return $this->redirectToRoute('app_annonces_offre_show', ['id' => $offre->getId(), 'slug' => $canonical], 301);
        }
        // Similar offers by localisation
        $similars = [];
        $cands = $offreRepo->findBy(['localisation' => $offre->getLocalisation()], ['createdAt' => 'DESC'], 6);
        foreach ($cands as $cand) {
            if ($cand->getId() === $offre->getId()) { continue; }
            $desc = $cand->getDescription() ?: '';
            if (mb_strlen($desc) > 120) { $desc = mb_substr($desc, 0, 117) . '…'; }
            $similars[] = [
                'title' => $cand->getTitre() ?: 'Offre',
                'description' => $desc ?: '—',
                'href' => $this->generateUrl('app_annonces_offre_show', ['id' => $cand->getId(), 'slug' => $cand->getSlug() ?: $this->slugify($cand->getTitre() ?? 'offre')]),
                'image' => 'https://images.pexels.com/photos/5582598/pexels-photo-5582598.jpeg?auto=compress&cs=tinysrgb&w=600',
            ];
            if (count($similars) >= 4) break;
        }
        if (count($similars) < 4) {
            $more = $offreRepo->findBy([], ['createdAt' => 'DESC'], 8);
            foreach ($more as $cand) {
                if ($cand->getId() === $offre->getId()) { continue; }
                $desc = $cand->getDescription() ?: '';
                if (mb_strlen($desc) > 120) { $desc = mb_substr($desc, 0, 117) . '…'; }
                $similars[] = [
                    'title' => $cand->getTitre() ?: 'Offre',
                    'description' => $desc ?: '—',
                    'href' => $this->generateUrl('app_annonces_offre_show', ['id' => $cand->getId(), 'slug' => $cand->getSlug() ?: $this->slugify($cand->getTitre() ?? 'offre')]),
                    'image' => 'https://images.pexels.com/photos/5582598/pexels-photo-5582598.jpeg?auto=compress&cs=tinysrgb&w=600',
                ];
                if (count($similars) >= 4) break;
            }
        }
        return $this->render('annonces/show_offre.html.twig', [
            'offre' => $offre,
            'similars' => $similars,
        ]);
    }

    #[Route('/annonces/demande/{id}-{slug}', name: 'app_annonces_demande_show', requirements: ['id' => '\\d+'])]
    public function showDemande(DemandeDeTravail $demande, string $slug, DemandeDeTravailRepository $demandeRepo): Response
    {
        $canonical = $demande->getSlug() ?: $this->slugify($demande->getTitre() ?? 'demande');
        if ($slug !== $canonical) {
            return $this->redirectToRoute('app_annonces_demande_show', ['id' => $demande->getId(), 'slug' => $canonical], 301);
        }
        $similars = [];
        $cands = $demandeRepo->findBy(['zoneAction' => $demande->getZoneAction()], ['createdAt' => 'DESC'], 6);
        foreach ($cands as $cand) {
            if ($cand->getId() === $demande->getId()) { continue; }
            $desc = $cand->getDescription() ?: '';
            if (mb_strlen($desc) > 120) { $desc = mb_substr($desc, 0, 117) . '…'; }
            $similars[] = [
                'title' => $cand->getTitre() ?: 'Demande',
                'description' => $desc ?: '—',
                'href' => $this->generateUrl('app_annonces_demande_show', ['id' => $cand->getId(), 'slug' => $cand->getSlug() ?: $this->slugify($cand->getTitre() ?? 'demande')]),
                'image' => 'https://images.pexels.com/photos/4247947/pexels-photo-4247947.jpeg?auto=compress&cs=tinysrgb&w=600',
            ];
            if (count($similars) >= 4) break;
        }
        if (count($similars) < 4) {
            $more = $demandeRepo->findBy([], ['createdAt' => 'DESC'], 8);
            foreach ($more as $cand) {
                if ($cand->getId() === $demande->getId()) { continue; }
                $desc = $cand->getDescription() ?: '';
                if (mb_strlen($desc) > 120) { $desc = mb_substr($desc, 0, 117) . '…'; }
                $similars[] = [
                    'title' => $cand->getTitre() ?: 'Demande',
                    'description' => $desc ?: '—',
                    'href' => $this->generateUrl('app_annonces_demande_show', ['id' => $cand->getId(), 'slug' => $cand->getSlug() ?: $this->slugify($cand->getTitre() ?? 'demande')]),
                    'image' => 'https://images.pexels.com/photos/4247947/pexels-photo-4247947.jpeg?auto=compress&cs=tinysrgb&w=600',
                ];
                if (count($similars) >= 4) break;
            }
        }
        return $this->render('annonces/show_demande.html.twig', [
            'demande' => $demande,
            'similars' => $similars,
        ]);
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        return $text ?: 'annonce';
    }
}
