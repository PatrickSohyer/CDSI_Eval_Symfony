<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Entity\Module;
use Faker\Factory;
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
        for ($a = 1; $a < 5; $a++) {
            $userForma = new Utilisateur;
            $userForma->setEmail($faker->email);
            $userForma->setRoles(['ROLE_FORMATEUR']);
            $userForma->setNom($faker->firstName);
            $userForma->setPrenom($faker->lastName);
            $password = $this->encoder->encodePassword($userForma, 'aden');
            $userForma->setPassword($password);
            $manager->persist($userForma);
            for ($b = 1; $b < 5; $b++) {
                $userEleve = new Utilisateur;
                $userEleve->setEmail($faker->email);
                $userEleve->setRoles(['ROLE_ETUDIANT']);
                $userEleve->setNom($faker->firstName);
                $userEleve->setPrenom($faker->lastName);
                $password = $this->encoder->encodePassword($userEleve, 'aden');
                $userEleve->setPassword($password);
                $manager->persist($userEleve);
                for ($c = 1; $c < 5; $c++) {
                    $forma = new Formation;
                    $forma->setNom($faker->words(5, true));
                    $forma->setDateDebut($faker->dateTimeBetween('+0 days', '+1 years'));
                    $forma->setDateFin($faker->dateTimeBetween('+2 years', '+5 years'));
                    $forma->getUtilisateurs([$userEleve]);
                    $manager->persist($forma);

                    for ($d = 1; $d <= mt_rand(6, 10); $d++) {
                        $module = new Module;
                        $module->setNom($faker->words(5, true));
                        $module->setNbHeures($faker->numberBetween(10, 50));
                        $module->setFormation($forma);
                        $manager->persist($module);
                    }
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
