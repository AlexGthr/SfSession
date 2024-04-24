<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Formateur;
use App\Form\StudentType;
use App\Form\FormateurType;
use App\Repository\StudentRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonneController extends AbstractController
{
    #[Route('/personne', name: 'app_personne')]
    public function index(StudentRepository $studentRepository): Response
    {
        $user = $this->getUser();

        if ($user) {

        $students = $studentRepository->findBy([], ['name' => 'ASC']);

        
        return $this->render('personne/index.html.twig', [
            'controller_name' => 'PersonneController',
            'students' => $students,
        ]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/formateur', name: 'app_formateur')]
    public function listeFormateurs(FormateurRepository $formateurRepository): Response
    {
        $user = $this->getUser();

        if ($user) {

        $formateurs = $formateurRepository->findBy([], ['name' => 'ASC']);

        
        return $this->render('personne/list_formateur.html.twig', [
            'controller_name' => 'PersonneController',
            'formateurs' => $formateurs,
        ]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour AJOUTER ou EDIT un STAGIAIRE
    #[Route('/personne/new', name: 'new_student')]
    #[Route('/personne/{id}/edit', name: 'edit_student')]
    public function new_editStudent(StudentRepository $studentRepository, Student $student = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {

        $user = $this->getUser();

        if ($user) {

        // Si il n'y a pas de STAGIAIRE,
        if (!$student) {
            // On crée un nouvel objet STAGIAIRE
            $student = new Student();
        } else {
            $student = $studentRepository->find($id);
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
            return $this->redirectToRoute('app_personne');
        }
                
        return $this->render('personne/newStudent.html.twig', [
            'formAddStudent' => $form,
            'student' => $student,
            'edit' => $student->getId()
        ]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour ajoutés un formateur ou pour l'EDIT
    #[Route('/formateur/new', name: 'new_formateur')]
    #[Route('/formateur/{id}/edit', name: 'edit_formateur')]
    public function new_editFormateur(FormateurRepository $formateurRepository, Formateur $formateur = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {

        $user = $this->getUser();

        if ($user) {

        // Si il n'y a pas de FORMATEUR,
        if (!$formateur) {
            // On crée un nouvel objet FORMATEUR
            $formateur = new Formateur();
        } else {
            $formateur = $formateurRepository->find($id);
        }
        
        
        // On crée le formulaire pour le FORMATEUR
        $form = $this->createForm(FormateurType::class, $formateur);
                
        $form->handleRequest($request);
                
        
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
                    
            // On recupère les données du formulaire
            $formateur = $form->getData();
        
            // PREPARE PDO
            $entityManager->persist($formateur);
            // EXECUTE PDO
            $entityManager->flush();
        
            // Puis on redirige l'user vers la liste des FORMATEUR
            return $this->redirectToRoute('app_formateur');
        }
                
        return $this->render('personne/newFormateur.html.twig', [
            'formAddFormateur' => $form,
            'formateur' => $formateur,
            'edit' => $formateur->getId()
        ]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour supprimer un stagiaire
    #[Route('/student/{id}/delete', name: 'delete_student')]
    public function delete(Student $student, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if ($user) {
        // Permet la suppression d'un stagiaire (delete from)
        $entityManager->remove($student);
        $entityManager->flush();

        // Puis on redirige l'user vers la liste des stagiaire
        return $this->redirectToRoute('app_personne');

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour supprimer un formateur
    #[Route('/formateur/{id}/delete', name: 'delete_formateur')]
    public function deleteFormateur(Formateur $formateur, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if ($user) {
        // Permet la suppression d'un stagiaire (delete from)
        $entityManager->remove($formateur);
        $entityManager->flush();

        // Puis on redirige l'user vers la liste des formateurs
        return $this->redirectToRoute('app_formateur');

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour afficher le detail d'un stagiaire
    #[Route('/student/{id}', name: 'show_student')]
    public function showFormation(StudentRepository $studentRepository, $id): Response 
    {
        $user = $this->getUser();

        if ($user) {
        
        $student = $studentRepository->find($id);

        if ($student) {
    
            return $this->render('personne/showStudent.html.twig', [
                'student' => $student
            ]);

        } else {
            return $this->redirectToRoute('app_personne');
        }

        }
        else {
            return $this->redirectToRoute('app_login');
        }

    }

    // Method pour afficher le detail d'un formateur
    #[Route('/formateur/{id}', name: 'show_formateur')]
    public function showFormateur(FormateurRepository $formateurRepository, $id): Response 
    {
        $user = $this->getUser();

        if ($user) {
        
        $formateur = $formateurRepository->find($id);

        if ($formateur) {
    
            return $this->render('personne/showFormateur.html.twig', [
                'formateur' => $formateur
            ]);

        } else {
            return $this->redirectToRoute('app_formateur');
        }

        }
        else {
            return $this->redirectToRoute('app_login');
        }

    }


    
}
