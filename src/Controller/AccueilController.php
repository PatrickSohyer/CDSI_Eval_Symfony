<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Seance;
use App\Form\ModuleType;
use App\Form\SeanceType;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\ModuleRepository;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AccueilController extends AbstractController
{
    /**
     * Fonction pour récupérer les formations
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
     * Formation pour récupérer une formation
     * @Security("is_granted('ROLE_ETUDIANT')")
     * @Route("/formation/{id}", name="formation_id")
     */
    public function formationId(Formation $formation, FormationRepository $repFormation)
    {
        if(!$formation->getUtilisateurs()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('accueil');
        }
        

        $listFormation = $repFormation->findAll();
        return $this->render('accueil/detailsFormation.html.twig', [
            "formation" => $formation,
            "listFormation" => $listFormation
        ]);
    }

    /**
     * Fonction pour ajouter une formation
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/ajoutFormation", name="ajout_formation")
     */
    public function ajoutFormation(Request $request, ObjectManager $manager)
    {
            $formation = new Formation;

        $formForma = $this->createForm(FormationType::class, $formation);
        $formForma->add('submit', SubmitType::class, array('label' => 'Ajouter Formation', 'attr' => ['class' => 'btn btn-dark mt-2']));
        $formForma->handleRequest($request);

        if ($formForma->isSubmitted() && $formForma->isValid()) {
            $manager->persist($formation);
            $manager->flush();
            return $this->redirectToRoute("formation_id", ['id' => $formation->getId()]);
        }

        return $this->render('accueil/creerFormation.html.twig', [
            "formForma" => $formForma->createView()
        ]);
    }

    /**
     * Fonction pour éditer une formation
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/editFormation/{id}", name="edit_formation")
     */
    public function editFormation(Formation $formation, Request $request, ObjectManager $manager)
    {

        $formForma = $this->createForm(FormationType::class, $formation);
        $formForma->add('submit', SubmitType::class, array('label' => 'Modifier Formation', 'attr' => ['class' => 'btn btn-dark mt-2']));
        $formForma->handleRequest($request);

        if ($formForma->isSubmitted() && $formForma->isValid()) {
            $manager->persist($formation);
            $manager->flush();
            return $this->redirectToRoute("formation_id", ['id' => $formation->getId()]);
        }

        return $this->render('accueil/creerFormation.html.twig', [
            "formForma" => $formForma->createView()
        ]);
    }

    /**
     * Fonction pour supprimer une formation
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/supprimerFormation/{id}", name="supprimer_formation")
     */
    public function supprimerFormation(Formation $formation, ObjectManager $manager)
    {
        $manager->remove($formation);
        $manager->flush();
        return $this->redirectToRoute("accueil");
    }

    /**
     * Fonction pour ajouter un module
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/ajoutModule", name="ajout_module")
     */
    public function ajoutModule(Request $request, ObjectManager $manager)
    {
            $module = new Module;

        $formModule = $this->createForm(ModuleType::class, $module);     
        $formModule->add('submit', SubmitType::class, array('label' => 'Ajouter Module', 'attr' => ['class' => 'btn btn-dark mt-2']));
        $formModule->handleRequest($request);

        if ($formModule->isSubmitted() && $formModule->isValid()) {
            $manager->persist($module);
            $manager->flush();
            return $this->redirectToRoute("accueil");
        }

        return $this->render('accueil/creerModule.html.twig', [
            "formModule" => $formModule->createView()
        ]);
    }

    /**
     * Fonction pour éditer un module
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/editModule/{id}", name="edit_module")
     */
    public function editModule(Module $module, Request $request, ObjectManager $manager)
    {

        $formModule = $this->createForm(ModuleType::class, $module);
        $formModule->add('submit', SubmitType::class, array('label' => 'Modifier Module', 'attr' => ['class' => 'btn btn-dark mt-2']));
        $formModule->handleRequest($request);

        if ($formModule->isSubmitted() && $formModule->isValid()) {
            $manager->persist($module);
            $manager->flush();
            return $this->redirectToRoute("accueil", ['id' => $module->getFormation()->getId()]);
        }

        return $this->render('accueil/creerModule.html.twig', [
            "formModule" => $formModule->createView()
        ]);
    }

    /**
     * Fonction pour supprimer un module
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/supprimerModule/{id}", name="supprimer_module")
     */
    public function supprimerModule(Module $module, ObjectManager $manager)
    {
        $manager->remove($module);
        $manager->flush();
        return $this->redirectToRoute("formation_id", ['id' => $module->getFormation()->getId()]);
    }

    /**
     * Fonction pour afficher un module, les séances et ajouter une séance
     * @Security("is_granted('ROLE_ETUDIANT')")
     * @Route("/module/{id}", name="module_id")
     */
    public function moduleId(Module $module, ModuleRepository $repModule, Seance $seance = null, Request $request, ObjectManager $manager, SluggerInterface $slugger, string $fichierDir, $id)
    {

        if(!$module->getFormation()->getUtilisateurs()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('accueil');
        }
       
        $seance = new Seance;
        $module = $repModule->find($id);

        $formSeance = $this->createForm(SeanceType::class, $seance);
        $formSeance->add('submit', SubmitType::class, array('label' => 'Ajouter une séance', 'attr' => ['class' => 'btn btn-dark mt-2']));
        $formSeance->handleRequest($request);

        if ($formSeance->isSubmitted() && $formSeance->isValid()) {
            if ($fichier = $formSeance['fichier']->getData()) {
                $filename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($filename);
                $newFilename = $safeFilename . "-" .uniqid(). "." . $fichier->guessExtension();
                try {
                    $fichier->move($fichierDir, $newFilename);
                } catch (FileException $e) {
                }
                $seance->setFichier($newFilename);
            }
            $seance->setModule($module);

            $manager->persist($seance);
            $manager->flush();
            return $this->redirectToRoute("module_id", ['id' => $module->getId()]);
        }

        $listModule = $repModule->findAll();
        return $this->render('accueil/detailsModule.html.twig', [
            "module" => $module,
            "listModule" => $listModule,
            "formSeance" => $formSeance->createView()
        ]);
    }

    /**
     * Fonction pour supprimer une séance
     * @Security("is_granted('ROLE_FORMATEUR')")
     * @Route("/supprimerSeance/{id}", name="supprimer_seance")
     */
    public function supprimerSeance(Seance $seance, ObjectManager $manager)
    {
        $manager->remove($seance);
        $manager->flush();
        return $this->redirectToRoute("module_id", ['id' => $seance->getModule()->getId()]);
    }
}
