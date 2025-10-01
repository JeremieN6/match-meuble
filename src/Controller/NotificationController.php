<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications', methods: ['GET'])]
    public function list(NotificationRepository $repository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $notifs = $repository->findBy(['userId' => $user], ['dateNotification' => 'DESC'], 20);
        $out = array_map(function ($n) {
            return [
                'id' => $n->getId(),
                'message' => $n->getMessageNotif(),
                'date' => $n->getDateNotification() ? $n->getDateNotification()->format('c') : null,
                'lu' => (bool)$n->isLu(),
            ];
        }, $notifs);
        return $this->json($out);
    }
}
