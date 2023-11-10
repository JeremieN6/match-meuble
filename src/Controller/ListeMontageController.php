<?php

namespace App\Controller;

use App\Entity\DemandeDeTravail;
use App\Entity\OffreDeTravail;
use App\Form\DemandeFormType;
use App\Form\OffreFormType;
use App\Repository\DemandeDeTravailRepository;
use App\Repository\OffreDeTravailRepository;
use App\Repository\StatusOffreRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ListeMontageController extends AbstractController
{

    #[Route('/formulaire-offre-de-montage', name: 'app_form_offre_montage')]
    public function offreMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        StatusOffreRepository $statusOffreRepository,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        //On crée le formulaire
        $offreMontageForm = $this->createForm(OffreFormType::class);

        //On traite la requête du formulaire
        $offreMontageForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($offreMontageForm->isSubmitted() && $offreMontageForm->isValid()){

            //Récupérer les réponses du formulaire
            $reponsesData = $offreMontageForm->getData();

            $description = $reponsesData['description'];
            $localisation = $reponsesData['localisation'];
            $remuneration = $reponsesData['remuneration'];
            $dateDebutMontage = $reponsesData['dateDebutMontage'];
            $dateFinMontage = $reponsesData['dateFinMontage'];

            //Paramétrer le status de l'offre à "Libre" par défaut
            $statusLibre = $statusOffreRepository->find(1);

            $offerMontageEntity = new OffreDeTravail();
            $offerMontageEntity->setUserId($connectedUser);
            $offerMontageEntity->setStatus($statusLibre);
            $offerMontageEntity->setDescription($description);
            $offerMontageEntity->setLocalisation($localisation);
            $offerMontageEntity->setRemuneration($remuneration);
            $offerMontageEntity->setDateDebutMontage($dateDebutMontage);
            $offerMontageEntity->setDateFinMontage($dateFinMontage);

             //envoie a l'entité
             $entityManager->persist($offerMontageEntity);
             $entityManager->flush();
 
             $flashy->success('Ton offre de montage a été validé avec succès ! 🚀');
 
             //On redirige
             return $this->redirectToRoute('app_liste_offre_montage');
        }
        

        return $this->render('formListes/offreMontage.html.twig', [
            'controller_name' => 'ListeMontageController',
            'offreMontageForm' => $offreMontageForm->createView(),
            'user' => $connectedUser,
        ]);
    }

    #[Route('/formulaire-demande-de-montage', name: 'app_form_demande_montage')]
    public function demandeMontage(
        Request $request,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        //On crée le formulaire
        $demandeMontageForm = $this->createForm(DemandeFormType::class);

        //On traite la requête du formulaire
        $demandeMontageForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($demandeMontageForm->isSubmitted() && $demandeMontageForm->isValid()){

            //Récupérer les réponses du formulaire
            $reponsesData = $demandeMontageForm->getData();

            $description = $reponsesData['description'];
            $disponibilite = $reponsesData['disponibilite'];
            $zoneAction = $reponsesData['zoneAction'];
            $salaire = $reponsesData['salaire'];
            
            $demandeMontageEntity = new DemandeDeTravail();
            $demandeMontageEntity->setUserId($connectedUser);
            $demandeMontageEntity->setDescription($description);
            $demandeMontageEntity->setDisponibilite($disponibilite);
            $demandeMontageEntity->setZoneAction($zoneAction);
            $demandeMontageEntity->setSalaire($salaire);
            $demandeMontageEntity->setCreatedAt(new DateTimeImmutable());

            //envoie a l'entité
            $entityManager->persist($demandeMontageEntity);
            $entityManager->flush();
 
            $flashy->success('Ta demande de montage a été validé avec succès ! 🚀');

            //On redirige
            return $this->redirectToRoute('app_liste_demande_montage');
        }


        return $this->render('formListes/demandeMontage.html.twig', [
            'controller_name' => 'ListeMontageController',
            'demandeMontageForm' => $demandeMontageForm->createView(),
            'user' => $connectedUser,
        ]);
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
            $statusId = $offre->getStatus()->getId();
            $statusName = $statusOffreRepository->find($statusId)->getNomStatus();
            $statusNames[$statusId] = $statusName;
        }


        return $this->render('listes/OffreMontage.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
            'listeOffreTravail' => $listeOffreTravail,
            'listeDemandeTravail' => $listeDemandeTravail,
            'statusNames' => $statusNames,
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


        return $this->render('listes/DemandeMontage.html.twig', [
            'controller_name' => 'ListeMontageController',
            'user' => $connectedUser,
            'listeOffreTravail' => $listeOffreTravail,
            'listeDemandeTravail' => $listeDemandeTravail
        ]);
    }
}