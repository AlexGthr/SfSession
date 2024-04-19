<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Category;
use App\Form\ModuleType;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\StudentType;
use App\Form\CategoryType;
use App\Form\FormationType;
use App\Form\ProgrammeType;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use App\Repository\CategoryRepository;
use App\Repository\FormationRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
        // LISTE DES FORMATIONS
    #[Route('/formation', name: 'app_formation')]
    public function index(FormationRepository $formationRepository): Response
    {

        $formation = $formationRepository->findBy([], ['id' => 'ASC']);


        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'formations' => $formation
        ]);
    }


    // Method pour AJOUTER ou EDIT une FORMATION
    #[Route('/formation/new', name: 'new_formation')]
    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    public function new_editFormation(FormationRepository $formationRepository, Formation $formation = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {
        // Si il n'y a pas de FORMATION,
        if (!$formation) {
            // On crée un nouvel objet FORMATION
            $formation = new Formation();
        } else {
            $formation = $formationRepository->find($id);
        }
            
            
        // On crée le formulaire pour la FORMATION
        $form = $this->createForm(FormationType::class, $formation);
                    
        $form->handleRequest($request);
                    
            
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
                        
            // On recupère les données du formulaire
            $formation = $form->getData();
            
            // PREPARE PDO
            $entityManager->persist($formation);
            // EXECUTE PDO
            $entityManager->flush();
            
            // Puis on redirige l'user vers la liste des FORMATION
            return $this->redirectToRoute('app_formation');
            }
                    
        return $this->render('formation/newFormation.html.twig', [
            'formAddFormation' => $form,
            'formation' => $formation,
            'edit' => $formation->getId()
            ]);
    }

    // Method pour AJOUTER ou EDIT un PROGRAMME
    #[Route('/programme/new', name: 'new_programme')]
    #[Route('/programme/{id}/edit', name: 'edit_programme')]
    public function new_editProgramme(Programme $programme = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Si il n'y a pas de PROGRAMME,
        if (!$programme) {
            // On crée un nouvel objet PROGRAMME
            $programme = new Programme();
        }
            
            
        // On crée le formulaire pour le PROGRAMME
        $form = $this->createForm(ProgrammeType::class, $programme);
                    
        $form->handleRequest($request);
                    
            
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
                        
            // On recupère les données du formulaire
            $programme = $form->getData();
            
            // PREPARE PDO
            $entityManager->persist($programme);
            // EXECUTE PDO
            $entityManager->flush();
            
            // Puis on redirige l'user vers la création d'un PROGRAMME
            return $this->redirectToRoute('new_programme');
            }
                    
        return $this->render('formation/newProgramme.html.twig', [
            'formAddProgramme' => $form,
            'edit' => $programme->getId()
            ]);
    }

    // Method pour afficher le detail d'une formation
    #[Route('/formation/{id}', name: 'show_formation')]
    public function showFormation(Formation $formation = null, SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository): Response 
    {

        if ($formation) {

            $sessions = $sessionRepository->findBy(['formation' => $formation]);
    
            return $this->render('formation/showFormation.html.twig', [
                'formation' => $formation,
                'sessions' => $sessions
            ]);

        } else {
            return $this->redirectToRoute('app_formation');
        }

    }
}
