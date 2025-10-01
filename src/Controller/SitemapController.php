<?php

namespace App\Controller;

use App\Repository\OffreDeTravailRepository;
use App\Repository\DemandeDeTravailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ['_format' => 'xml'])]
    public function index(OffreDeTravailRepository $offres, DemandeDeTravailRepository $demandes): Response
    {
        $urls = [];
    $urls[] = [ 'loc' => $this->generateUrl('app_home') ];
    $urls[] = [ 'loc' => $this->generateUrl('app_annonces') ];

        foreach ($offres->findBy([], ['updatedAt' => 'DESC'], 500) as $o) {
            $urls[] = [
                'loc' => $this->generateUrl('app_annonces_offre_show', ['id' => $o->getId(), 'slug' => $o->getSlug() ?: 'offre']),
                'lastmod' => $o->getUpdatedAt() ? $o->getUpdatedAt()->format('c') : null,
            ];
        }
        foreach ($demandes->findBy([], ['updatedAt' => 'DESC'], 500) as $d) {
            $urls[] = [
                'loc' => $this->generateUrl('app_annonces_demande_show', ['id' => $d->getId(), 'slug' => $d->getSlug() ?: 'demande']),
                'lastmod' => $d->getUpdatedAt() ? $d->getUpdatedAt()->format('c') : null,
            ];
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');
        foreach ($urls as $u) {
            $url = $xml->addChild('url');
            $url->addChild('loc', htmlspecialchars($this->getBaseUrl() . $u['loc']));
            if (!empty($u['lastmod'])) { $url->addChild('lastmod', $u['lastmod']); }
        }
        $resp = new Response($xml->asXML());
        $resp->headers->set('Content-Type', 'application/xml');
        return $resp;
    }

    private function getBaseUrl(): string
    {
        $req = $this->get('request_stack')->getCurrentRequest();
        if (!$req) { return ''; }
        return $req->getSchemeAndHttpHost();
    }
}
