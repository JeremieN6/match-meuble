<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/notifications/count', name: 'app_notifications_count', methods: ['GET'])]
    public function count(NotificationRepository $repository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $count = $repository->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.userId = :u')
            ->andWhere('n.lu = 0 OR n.lu IS NULL')
            ->setParameter('u', $user)
            ->getQuery()->getSingleScalarResult();
        return $this->json(['count' => (int)$count]);
    }

    #[Route('/notifications/read', name: 'app_notifications_read', methods: ['POST'])]
    public function markRead(Request $request, NotificationRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->isCsrfTokenValid('notif', $token)) {
            return $this->json(['error' => 'Invalid CSRF token'], 400);
        }
        $data = json_decode($request->getContent(), true) ?? [];
        $user = $this->getUser();
        if (!empty($data['all'])) {
            $list = $repository->findBy(['userId' => $user, 'lu' => false]);
            foreach ($list as $n) { $n->setLu(true); }
            $em->flush();
        } elseif (!empty($data['ids']) && is_array($data['ids'])) {
            $ids = array_map('intval', $data['ids']);
            if ($ids) {
                $qb = $repository->createQueryBuilder('n')
                    ->where('n.userId = :u')->setParameter('u', $user)
                    ->andWhere('n.id IN (:ids)')->setParameter('ids', $ids);
                /** @var Notification[] $list */
                $list = $qb->getQuery()->getResult();
                foreach ($list as $n) { $n->setLu(true); }
                $em->flush();
            }
        }
        // return new unread count
        $count = $repository->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.userId = :u')
            ->andWhere('n.lu = 0 OR n.lu IS NULL')
            ->setParameter('u', $user)
            ->getQuery()->getSingleScalarResult();
        return $this->json(['ok' => true, 'count' => (int)$count]);
    }

    #[Route('/notifications/{id}', name: 'app_notifications_delete', methods: ['DELETE'])]
    public function delete(Notification $notification, Request $request, NotificationRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->isCsrfTokenValid('notif', $token)) {
            return $this->json(['error' => 'Invalid CSRF token'], 400);
        }
        $user = $this->getUser();
        if ($notification->getUserId() === null || $notification->getUserId()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Forbidden'], 403);
        }
        $em->remove($notification);
        $em->flush();
        $count = $repository->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.userId = :u')
            ->andWhere('n.lu = 0 OR n.lu IS NULL')
            ->setParameter('u', $user)
            ->getQuery()->getSingleScalarResult();
        return $this->json(['ok' => true, 'count' => (int)$count]);
    }
}
