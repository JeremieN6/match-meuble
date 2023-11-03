<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ListeMontageController extends AbstractController
{
    #[Route('/liste-demande-pour-montage', name: 'app_demande_montage')]
    public function demandeMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        if($connectedUser){
          

        }
        return $this->render('listes/demandeMontage.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $connectedUser,
        ]);
    }

    #[Route('/liste-offre-de-montage', name: 'app_offre_montage')]
    public function offreMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        if($connectedUser){
          

        }
        return $this->render('listes/offreMontage.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $connectedUser,
        ]);
    }
}