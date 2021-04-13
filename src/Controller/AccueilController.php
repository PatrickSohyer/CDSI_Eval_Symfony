<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AccueilController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ETUDIANT')")
     * @Route("/accueil", name="accueil")
     */
    public function index(FormationRepository $repFormation): Response
    {
        $listFormation = $repFormation->findAll();
        return $this->render('accueil/index.html.twig', [
            "listFormation" => $listFormation
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ETUDIANT')")
     * @Route("/formation/{id}", name="formation_id")
     */
    public function formationId(Formation $formation, FormationRepository $repFormation)
    {
        $listFormation = $repFormation->findAll();
        return $this->render('accueil/detailsFormation.html.twig', [
            "formation" => $formation,
            "listFormation" => $listFormation
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/ajoutFormation", name="ajout_formation")
     * @Route("/editFormation/{id}", name="edit_formation")
     */
    public function ajoutFormation(Formation $formation = null, Request $request, ObjectManager $manager)
    {
        if (!$formation) {
            $formation = new Formation;
        }

        $formForma = $this->createForm(FormationType::class, $formation);
        $formForma->handleRequest($request);

        if ($formForma->isSubmitted() && $formForma->isValid()) {
            $manager->persist($formation);
            $manager->flush();
            return $this->redirectToRoute("formation_id", ['id' => $formation->getId()]);
        }

        return $this->render('accueil/creerFormation.html.twig', [
            "formForma" => $formForma->createView(),
            "editMode" => $formation->getId() !== null,
        ]);
    }
}
