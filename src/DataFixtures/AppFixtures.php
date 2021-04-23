<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Module;
use App\Entity\Seance;
use App\Entity\Formation;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // Fixtures pour créer des formateurs
        for ($i = 1; $i < 5; $i++) {
            $userForma = new Utilisateur;
            $userForma->setEmail($faker->email);
            $userForma->setRoles(['ROLE_FORMATEUR']);
            $userForma->setNom($faker->firstName);
            $userForma->setPrenom($faker->lastName);
            $password = $this->encoder->encodePassword($userForma, 'aden');
            $userForma->setPassword($password);
            $manager->persist($userForma);
        }

        // Fixtures pour créer des étudiants
        for ($i = 1; $i < 5; $i++) {
            $userEleve = new Utilisateur;
            $userEleve->setEmail($faker->email);
            $userEleve->setRoles(['ROLE_ETUDIANT']);
            $userEleve->setNom($faker->firstName);
            $userEleve->setPrenom($faker->lastName);
            $password = $this->encoder->encodePassword($userEleve, 'aden');
            $userEleve->setPassword($password);
            $manager->persist($userEleve);
        }

        // Fixtures pour créer des formations
        for ($i = 1; $i < 5; $i++) {
            $forma = new Formation;
            $forma->setNom($faker->words(5, true));
            $forma->setDateDebut($faker->dateTimeBetween('+0 days', '+1 years'));
            $forma->setDateFin($faker->dateTimeBetween('+2 years', '+5 years'));
            $manager->persist($forma);
            // Fixtures pour créer des modules
            for ($j = 1; $j <= mt_rand(6, 10); $j++) {
                $module = new Module;
                $module->setNom($faker->words(5, true));
                $module->setNbHeures($faker->numberBetween(10, 50));
                $module->setFormation($forma);
                $manager->persist($module);
                for ($k = 1; $k <= mt_rand(1, 10); $k++) {
                    $seance = new Seance;
                    $seance->setDateSeance($faker->dateTimeBetween('+100 days', '+3 years'));
                    $seance->setDuree($faker->randomFloat(2, 1, 4));
                    $seance->setTitre($faker->words(3, true));
                    $seance->setContenu($faker->paragraphs(2, true));
                    $seance->setModule($module);
                    $manager->persist($seance);
                }
            }
        }

        $utilisateurAdmin = new Utilisateur;
        $utilisateurAdmin->setEmail('gthierry@gmail.com');
        $utilisateurAdmin->setRoles(['ROLE_ADMIN']);
        $utilisateurAdmin->setNom('Thiery');
        $utilisateurAdmin->setPrenom('Guillaume');
        $password = $this->encoder->encodePassword($utilisateurAdmin, 'aden');
        $utilisateurAdmin->setPassword($password);
        $manager->persist($utilisateurAdmin);

        $manager->flush();
    }
}
