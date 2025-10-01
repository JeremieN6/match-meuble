<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\OffreDeTravail;
use App\Entity\DemandeDeTravail;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message/offre/{id}', name: 'send_message_offre', methods: ['POST'])]
    public function sendForOffre(OffreDeTravail $offre, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $data = json_decode($request->getContent(), true) ?? [];
        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->isCsrfTokenValid('msg_offre'.$offre->getId(), $token)) {
            return $this->json(['error' => 'Invalid CSRF token'], 400);
        }
        $msgText = trim((string)($data['message'] ?? ''));
        if ($msgText === '') { return $this->json(['error' => 'Message vide'], 400); }

        $dest = $offre->getUserId();
        if (!$dest) { return $this->json(['error' => 'Destinataire introuvable'], 400); }

        $message = new Message();
        $message->setExpediteurId($this->getUser());
        $message->setDestinataireId($dest);
        $message->setContenuMessage($msgText);
        $message->setDateEnvoi(new \DateTime());

        $em->persist($message);
    // Create in-app notification for recipient
    $notif = new Notification();
    $notif->setUserId($dest);
    $notif->setMessageNotif('Nouveau message concernant une offre: '.mb_substr($msgText, 0, 120));
    $notif->setDateNotification(new \DateTime());
    $notif->setLu(false);
    $em->persist($notif);
    $em->flush();
        return $this->json(['ok' => true]);
    }

    #[Route('/message/demande/{id}', name: 'send_message_demande', methods: ['POST'])]
    public function sendForDemande(DemandeDeTravail $demande, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $data = json_decode($request->getContent(), true) ?? [];
        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->isCsrfTokenValid('msg_demande'.$demande->getId(), $token)) {
            return $this->json(['error' => 'Invalid CSRF token'], 400);
        }
        $msgText = trim((string)($data['message'] ?? ''));
        if ($msgText === '') { return $this->json(['error' => 'Message vide'], 400); }

        $dest = $demande->getUserId();
        if (!$dest) { return $this->json(['error' => 'Destinataire introuvable'], 400); }

        $message = new Message();
        $message->setExpediteurId($this->getUser());
        $message->setDestinataireId($dest);
        $message->setContenuMessage($msgText);
        $message->setDateEnvoi(new \DateTime());

        $em->persist($message);
    // Create in-app notification for recipient
    $notif = new Notification();
    $notif->setUserId($dest);
    $notif->setMessageNotif('Nouveau message concernant une demande: '.mb_substr($msgText, 0, 120));
    $notif->setDateNotification(new \DateTime());
    $notif->setLu(false);
    $em->persist($notif);
    $em->flush();
        return $this->json(['ok' => true]);
    }
}
