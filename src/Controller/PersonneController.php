<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonneController extends AbstractController
{
    #[Route('/personne', name: 'app_personne')]
    public function index(): Response
    {
        return $this->render('personne/index.html.twig', [
            'controller_name' => 'PersonneController',
        ]);
    }

    // Method pour afficher le detail d'une personne
    #[Route('/student/{id}', name: 'show_student')]
    public function showFormation(StudentRepository $studentRepository, $id): Response 
    {
        
        $student = $studentRepository->find($id);

        if ($student) {
    
            return $this->render('formation/details/showStudent.html.twig', [
                'student' => $student
            ]);

        } else {
            return $this->redirectToRoute('app_home');
        }

    }
}
