<?php

namespace App\DataFixtures;

use App\Entity\DemandeDeTravail;
use App\Entity\OffreDeTravail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 5 offres de montage
        for ($i = 0; $i < 5; $i++) {
            $offre = new OffreDeTravail();
            $offre->setTitre($faker->randomElement([
                'Montage armoire PAX (IKEA)',
                'Installation meuble TV mural',
                'Montage bibliothèque sur mesure',
                "Pose dressing d'angle",
                'Montage lit coffre 160x200',
            ]));
            $offre->setDescription($faker->paragraph(2));
            $offre->setLocalisation($faker->city());
            $offre->setRemuneration($faker->numberBetween(50, 400));
            $offre->setDateDebutMontage($faker->dateTimeBetween('now', '+10 days'));
            $offre->setDateFinMontage($faker->dateTimeBetween('+11 days', '+30 days'));
            $offre->setCreatedAt(new \DateTimeImmutable());
            $offre->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($offre);
        }

        // 5 demandes de montage
        for ($i = 0; $i < 5; $i++) {
            $demande = new DemandeDeTravail();
            $demande->setTitre($faker->randomElement([
                'Besoin montage commode 6 tiroirs',
                'Assemblage bureau + chaise',
                'Fixation étagères murales',
                "Montage armoire d'entrée",
                'Lit enfant + table de chevet',
            ]));
            $demande->setDescription($faker->paragraph(2));
            $demande->setDisponibilite($faker->randomElement(['Soirées', 'Week-ends', 'En journée']));
            $demande->setSalaire($faker->numberBetween(15, 40));
            $demande->setZoneAction($faker->randomElement(['5 km', '10 km', '15 km', '20 km']));
            $demande->setCreatedAt(new \DateTimeImmutable());
            $demande->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($demande);
        }

        $manager->flush();
    }
}
