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
    public function index(ProgrammeRepository $programmeRepository, SessionRepository $sessionRepository): Response
    {

        $programmes = $programmeRepository->findBy([], ['id' => 'ASC']);
        $sessions = $sessionRepository->findBy([], ['id' => 'ASC']);

        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'programmes' => $programmes,
            'sessions' => $sessions
        ]);
    }

    // Method pour afficher le detail d'une formation
    #[Route('/formation/{id}', name: 'show_formation')]
    public function showFormation(SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository, $id): Response 
    {
        
        $session = $sessionRepository->find($id);

        if ($session) {

            $programmes = $programmeRepository->findBy(['session' => $id]);
    
            return $this->render('formation/details/showFormation.html.twig', [
                'session' => $session,
                'programmes' => $programmes
            ]);

        } else {
            return $this->redirectToRoute('app_home');
        }

    }

            // LISTE DES SESSIONS
    #[Route('/session', name: 'app_listSession')]
    public function listSession(ProgrammeRepository $programmeRepository): Response
    {

        $sessions = $programmeRepository->findBy([], ['id' => 'ASC']);

        return $this->render('formation/listSession.html.twig', [
            'controller_name' => 'FormationController',
            'sessions' => $sessions
        ]);
    }

            // LISTE DES MODULES
    #[Route('/module', name: 'app_listModule')]
    public function listModule(ModuleRepository $moduleRepository): Response
    {

        $modules = $moduleRepository->findBy([], ['name' => 'ASC']);

        return $this->render('formation/listModule.html.twig', [
            'controller_name' => 'FormationController',
            'modules' => $modules
        ]);
    }

    // Method pour afficher le detail d'un module
    #[Route('/module/{id}', name: 'show_module')]
    public function showModule(Module $module): Response 
    {
        return $this->render('formation/details/showModule.html.twig', [
            'module' => $module
        ]);
    }

            // LISTE DES CATEGORIES
    #[Route('/category', name: 'app_listCategory')]
    public function listCategory(CategoryRepository $categoryRepository): Response
    {

        $categorys = $categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('formation/listCategory.html.twig', [
            'controller_name' => 'FormationController',
            'categorys' => $categorys
        ]);
    }

            // LISTE DES STAGIAIRE
    #[Route('/student', name: 'app_listStudent')]
    public function listStudent(StudentRepository $studentRepository): Response
    {

        $students = $studentRepository->findBy([], ['name' => 'ASC']);

        return $this->render('formation/listStudent.html.twig', [
            'controller_name' => 'FormationController',
            'students' => $students
        ]);
    }

    // Method pour AJOUTER ou EDIT une CATEGORIE
    #[Route('/category/new', name: 'new_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]
    public function new_editCategory(Category $category = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Si il n'y a pas CATEGORIE,
        if (!$category) {
            // On crée un nouvel objet CATEGORIE
            $category = new Category();
        }


        // On crée le formulaire pour la CATEGORIE
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        

        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
            
            // On recupère les données du formulaire
            $category = $form->getData();

            // PREPARE PDO
            $entityManager->persist($category);
            // EXECUTE PDO
            $entityManager->flush();

            // Puis on redirige l'user vers la liste des CATEGORIE
            return $this->redirectToRoute('app_listCategory');
        }
        
        return $this->render('formation/forms/newCateg.html.twig', [
            'formAddCategory' => $form,
            'edit' => $category->getId()
        ]);
    }

    // Method pour AJOUTER ou EDIT un STAGIAIRE
    #[Route('/student/new', name: 'new_student')]
    #[Route('/student/{id}/edit', name: 'edit_student')]
    public function new_editStudent(Student $student = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Si il n'y a pas de STAGIAIRE,
        if (!$student) {
            // On crée un nouvel objet STAGIAIRE
            $student = new Student();
        }
    
    
        // On crée le formulaire pour le STAGIAIRE
        $form = $this->createForm(StudentType::class, $student);
            
        $form->handleRequest($request);
            
    
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
                
            // On recupère les données du formulaire
            $student = $form->getData();
    
            // PREPARE PDO
            $entityManager->persist($student);
            // EXECUTE PDO
            $entityManager->flush();
    
            // Puis on redirige l'user vers la liste des STAGIAIRE
            return $this->redirectToRoute('app_listStudent');
        }
            
        return $this->render('formation/forms/newStudent.html.twig', [
            'formAddStudent' => $form,
            'edit' => $student->getId()
        ]);
    }

    // Method pour AJOUTER ou EDIT un MODULE
    #[Route('/module/new', name: 'new_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function new_editModule(Module $module = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Si il n'y a pas MODULE,
        if (!$module) {
            // On crée un nouvel objet MODULE
            $module = new Module();
        }
            
            
        // On crée le formulaire pour le MODULE
        $form = $this->createForm(ModuleType::class, $module);
                    
        $form->handleRequest($request);
                    
            
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
                        
            // On recupère les données du formulaire
            $module = $form->getData();
            
            // PREPARE PDO
            $entityManager->persist($module);
            // EXECUTE PDO
            $entityManager->flush();
            
            // Puis on redirige l'user vers la liste des MODULE
            return $this->redirectToRoute('app_listModule');
            }
                    
        return $this->render('formation/forms/newModule.html.twig', [
            'formAddModule' => $form,
            'edit' => $module->getId()
            ]);
    }

    // Method pour AJOUTER ou EDIT une SESSION
    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_editSession(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Si il n'y a pas de SESSION,
        if (!$session) {
            // On crée un nouvel objet SESSION
            $session = new Session();
        }
            
            
        // On crée le formulaire pour la SESSION
        $form = $this->createForm(SessionType::class, $session);
                    
        $form->handleRequest($request);
                    
            
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
                        
            // On recupère les données du formulaire
            $session = $form->getData();
            
            // PREPARE PDO
            $entityManager->persist($session);
            // EXECUTE PDO
            $entityManager->flush();
            
            // Puis on redirige l'user vers la liste des SESSION
            return $this->redirectToRoute('app_listSession');
            }
                    
        return $this->render('formation/forms/newSession.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId()
            ]);
    }

    // Method pour AJOUTER ou EDIT une FORMATION
    #[Route('/formation/new', name: 'new_formation')]
    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    public function new_editFormation(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Si il n'y a pas de FORMATION,
        if (!$formation) {
            // On crée un nouvel objet FORMATION
            $formation = new Formation();
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
                    
        return $this->render('formation/forms/newFormation.html.twig', [
            'formAddFormation' => $form,
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
                    
        return $this->render('formation/forms/newProgramme.html.twig', [
            'formAddProgramme' => $form,
            'edit' => $programme->getId()
            ]);
    }
}
