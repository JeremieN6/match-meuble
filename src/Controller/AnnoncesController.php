<?php

namespace App\Controller;

use App\Entity\DemandeDeTravail;
use App\Entity\OffreDeTravail;
use App\Repository\DemandeDeTravailRepository;
use App\Repository\OffreDeTravailRepository;
use App\Repository\EvaluationRepository;
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
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = min(30, max(6, (int)$request->query->get('perPage', 12)));
        $offset = ($page - 1) * $perPage;

        $q = trim((string)$request->query->get('q', ''));
        $min = $request->query->has('min') ? (int)$request->query->get('min') : null;
        $max = $request->query->has('max') ? (int)$request->query->get('max') : null;
        $status = $request->query->get('status');
        $type = $request->query->get('type'); // 'offre' | 'demande' | null

        // Simple query builders for perf (no full-text yet)
        $ob = $offres->createQueryBuilder('o')->orderBy('o.createdAt', 'DESC');
        if ($min !== null) { $ob->andWhere('o.remuneration >= :omin')->setParameter('omin', $min); }
        if ($max !== null) { $ob->andWhere('o.remuneration <= :omax')->setParameter('omax', $max); }
        if ($status) { $ob->join('o.status', 's')->andWhere('s.nomStatus = :st')->setParameter('st', $status); }
        if ($q !== '') {
            $ob->andWhere('LOWER(o.titre) LIKE :q OR LOWER(o.description) LIKE :q OR LOWER(o.localisation) LIKE :q')
               ->setParameter('q', '%'.mb_strtolower($q).'%');
        }
        $ob->setFirstResult($offset)->setMaxResults($perPage);
        $allOffres = ($type === 'demande') ? [] : $ob->getQuery()->getResult();

        $db = $demandes->createQueryBuilder('d')->orderBy('d.createdAt', 'DESC');
        if ($min !== null) { $db->andWhere('d.salaire >= :dmin')->setParameter('dmin', $min); }
        if ($max !== null) { $db->andWhere('d.salaire <= :dmax')->setParameter('dmax', $max); }
        if ($q !== '') {
            $db->andWhere('LOWER(d.titre) LIKE :qd OR LOWER(d.description) LIKE :qd OR LOWER(d.zoneAction) LIKE :qd')
               ->setParameter('qd', '%'.mb_strtolower($q).'%');
        }
        $db->setFirstResult($offset)->setMaxResults($perPage);
        $allDemandes = ($type === 'offre') ? [] : $db->getQuery()->getResult();

        $offreCards = [];
        foreach ($allOffres as $offre) {
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
                'status' => $offre->getStatus() ? $offre->getStatus()->getNomStatus() : null,
                'amount' => $offre->getRemuneration(),
                'created' => $offre->getCreatedAt() ? $offre->getCreatedAt()->getTimestamp() : 0,
                'favUrl' => $this->generateUrl('toggle_favorite_offre', ['id' => $offre->getId()]),
                'favToken' => $this->container->get('security.csrf.token_manager')->getToken('fav_offre'.$offre->getId())->getValue(),
            ];
        }

    $demandeCards = [];
    foreach ($allDemandes as $demande) {
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
                'status' => null,
                'amount' => $demande->getSalaire(),
                'created' => $demande->getCreatedAt() ? $demande->getCreatedAt()->getTimestamp() : 0,
                'favUrl' => $this->generateUrl('toggle_favorite_demande', ['id' => $demande->getId()]),
                'favToken' => $this->container->get('security.csrf.token_manager')->getToken('fav_demande'.$demande->getId())->getValue(),
            ];
        }

        $em->flush();
        $cards = array_merge($offreCards, $demandeCards);
        $hasMore = count($allOffres) === $perPage || count($allDemandes) === $perPage;

        return $this->render('annonces/index.html.twig', [
            'cards' => $cards,
            'page' => $page,
            'perPage' => $perPage,
            'hasMore' => $hasMore,
            'filters' => [ 'q' => $q, 'min' => $min, 'max' => $max, 'status' => $status, 'type' => $type ],
        ]);
    }

    #[Route('/annonces/offre/{id}-{slug}', name: 'app_annonces_offre_show', requirements: ['id' => '\\d+'])]
    public function showOffre(OffreDeTravail $offre, string $slug, OffreDeTravailRepository $offreRepo, EvaluationRepository $evalRepo): Response
    {
        $canonical = $offre->getSlug() ?: $this->slugify($offre->getTitre() ?? 'offre');
        if ($slug !== $canonical) {
            return $this->redirectToRoute('app_annonces_offre_show', ['id' => $offre->getId(), 'slug' => $canonical], 301);
        }
        // Author rating
        $author = $offre->getUserId();
        $authorAvg = null; $authorCount = 0;
        if ($author) {
            $ratings = $evalRepo->findBy(['userIdCible' => $author]);
            $authorCount = count($ratings);
            if ($authorCount > 0) {
                $sum = 0; foreach ($ratings as $r) { $sum += (int)($r->getNote() ?? 0); }
                $authorAvg = round($sum / $authorCount, 1);
            }
        }
        // Similar offers
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
            'authorAvg' => $authorAvg,
            'authorCount' => $authorCount,
        ]);
    }

    #[Route('/annonces/demande/{id}-{slug}', name: 'app_annonces_demande_show', requirements: ['id' => '\\d+'])]
    public function showDemande(DemandeDeTravail $demande, string $slug, DemandeDeTravailRepository $demandeRepo, EvaluationRepository $evalRepo): Response
    {
        $canonical = $demande->getSlug() ?: $this->slugify($demande->getTitre() ?? 'demande');
        if ($slug !== $canonical) {
            return $this->redirectToRoute('app_annonces_demande_show', ['id' => $demande->getId(), 'slug' => $canonical], 301);
        }
        // Author rating
        $author = $demande->getUserId();
        $authorAvg = null; $authorCount = 0;
        if ($author) {
            $ratings = $evalRepo->findBy(['userIdCible' => $author]);
            $authorCount = count($ratings);
            if ($authorCount > 0) {
                $sum = 0; foreach ($ratings as $r) { $sum += (int)($r->getNote() ?? 0); }
                $authorAvg = round($sum / $authorCount, 1);
            }
        }
        // Similar demandes
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
            'authorAvg' => $authorAvg,
            'authorCount' => $authorCount,
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
