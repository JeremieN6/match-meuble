<?php

namespace App\Controller;


use App\Form\DemandeFormType;
use App\Form\OffreFormType;
use App\Entity\DemandeDeTravail;
use App\Entity\OffreDeTravail;
use App\Repository\StatusOffreRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormulaireMontageController extends AbstractController
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

            $titre = $reponsesData['titre'];
            $description = $reponsesData['description'];
            $localisation = $reponsesData['localisation'];
            $remuneration = $reponsesData['remuneration'];
            $dateDebutMontage = $reponsesData['dateDebutMontage'];
            $dateFinMontage = $reponsesData['dateFinMontage'];

            //Paramétrer le status de l'offre à "Libre" par défaut
            $statusLibre = $statusOffreRepository->find(1);

            $offerMontageEntity = new OffreDeTravail();
            $offerMontageEntity->setUserId($connectedUser);
            $offerMontageEntity->settitre($titre);
            $offerMontageEntity->setDescription($description);
            $offerMontageEntity->setStatus($statusLibre);
            $offerMontageEntity->setLocalisation($localisation);
            $offerMontageEntity->setRemuneration($remuneration);
            $offerMontageEntity->setDateDebutMontage($dateDebutMontage);
            $offerMontageEntity->setDateFinMontage($dateFinMontage);
            // slug
            $offerMontageEntity->setSlug($this->slugify($titre));

             //envoie a l'entité
             $entityManager->persist($offerMontageEntity);
             $entityManager->flush();
 
             $flashy->success('Ton offre de montage a été validé avec succès ! 🚀');
 
             //On redirige
             return $this->redirectToRoute('app_liste_offre_montage');
        }
        

        return $this->render('formulaire_montage/offre.html.twig', [
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

            $titre = $reponsesData['titre'];
            $description = $reponsesData['description'];
            $disponibilite = $reponsesData['disponibilite'];
            $zoneAction = $reponsesData['zoneAction'];
            $salaire = $reponsesData['salaire'];
            
            $demandeMontageEntity = new DemandeDeTravail();
            $demandeMontageEntity->setUserId($connectedUser);
            $demandeMontageEntity->setTitre($titre);
            $demandeMontageEntity->setDescription($description);
            $demandeMontageEntity->setDisponibilite($disponibilite);
            $demandeMontageEntity->setZoneAction($zoneAction);
            $demandeMontageEntity->setSalaire($salaire);
            $demandeMontageEntity->setCreatedAt(new DateTimeImmutable());
            // slug
            $demandeMontageEntity->setSlug($this->slugify($titre));

            //envoie a l'entité
            $entityManager->persist($demandeMontageEntity);
            $entityManager->flush();
 
            $flashy->success('Ta demande de montage a été validé avec succès ! 🚀');

            //On redirige
            return $this->redirectToRoute('app_liste_demande_montage');
        }


        return $this->render('formulaire_montage/demande.html.twig', [
            'controller_name' => 'ListeMontageController',
            'demandeMontageForm' => $demandeMontageForm->createView(),
            'user' => $connectedUser,
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
