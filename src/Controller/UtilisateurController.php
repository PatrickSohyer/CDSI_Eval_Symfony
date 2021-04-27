<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditProfilType;
use App\Form\UtilisateurType;
use Doctrine\Persistence\ObjectManager;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur;

        $formUtilisateur = $this->createForm(UtilisateurType::class, $utilisateur);
        $formUtilisateur->add('submit', SubmitType::class, array('label' => 'Inscription', 'attr' => ['class' => 'bg-dark text-white mt-3']));
        $formUtilisateur->handleRequest($request);

        if ($formUtilisateur->isSubmitted() && $formUtilisateur->isValid()) {
            $password = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);
            $manager->persist($utilisateur);
            $manager->flush();
            return $this->redirectToRoute("accueil");
        }

        return $this->render('utilisateur/inscription.html.twig', [
            "formUtilisateur" => $formUtilisateur->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/editProfil/{id}", name="edit_profil")
     */
    public function editProfil(Utilisateur $utilisateur, Request $request, ObjectManager $manager)
    {
        $formEditUser = $this->createForm(EditProfilType::class, $utilisateur);
        $formEditUser->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $formEditUser->handleRequest($request);

        if ($formEditUser->isSubmitted() && $formEditUser->isValid()) {
            $manager->persist($utilisateur);
            $manager->flush();
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute("accueil");
        }

        return $this->render('utilisateur/editProfil.html.twig', [
            "formEditUser" => $formEditUser->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/supprimerUtilisateur/{id}", name="supprimer_utilisateur")
     */
    public function supprimerUtilisateur(Utilisateur $utilisateur, ObjectManager $manager)
    {
        $manager->remove($utilisateur);
        $manager->flush();
        return $this->redirectToRoute("liste_utilisateur");
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/utilisateur", name="liste_utilisateur")
     */
    public function index(UtilisateurRepository $repUtilisateur): Response
    {
        $listUtilisateur = $repUtilisateur->findAll();
        return $this->render('utilisateur/listUtilisateur.html.twig', [
            "listUtilisateur" => $listUtilisateur
        ]);
    }
}
