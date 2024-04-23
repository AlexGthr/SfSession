<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
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

        $students = $studentRepository->findBy([], ['name' => 'ASC']);
        
        return $this->render('personne/index.html.twig', [
            'controller_name' => 'PersonneController',
            'students' => $students
        ]);
    }

    // Method pour AJOUTER ou EDIT un STAGIAIRE
    #[Route('/personne/new', name: 'new_student')]
    #[Route('/personne/{id}/edit', name: 'edit_student')]
    public function new_editStudent(StudentRepository $studentRepository, Student $student = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {
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

    // Method pour supprimer un stagiaire
    #[Route('/student/{id}/delete', name: 'delete_student')]
    public function delete(Student $student, EntityManagerInterface $entityManager)
    {
        // Permet la suppression d'un stagiaire (delete from)
        $entityManager->remove($student);
        $entityManager->flush();

        // Puis on redirige l'user vers la liste des stagiaire
        return $this->redirectToRoute('app_personne');
    }

    // Method pour afficher le detail d'une personne
    #[Route('/student/{id}', name: 'show_student')]
    public function showFormation(StudentRepository $studentRepository, $id): Response 
    {
        
        $student = $studentRepository->find($id);

        if ($student) {
    
            return $this->render('personne/showStudent.html.twig', [
                'student' => $student
            ]);

        } else {
            return $this->redirectToRoute('app_personne');
        }

    }


    
}
