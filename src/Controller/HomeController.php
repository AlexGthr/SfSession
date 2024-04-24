<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use App\Repository\CategoryRepository;
use App\Repository\FormateurRepository;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'sessions' => $sessions
        ]);
    }

    #[Route('/recherche/{key}', name: 'app_recherche')]
    public function recherche(CategoryRepository $categoryRepository, FormateurRepository $formateurRepository, FormationRepository $formationRepository, ModuleRepository $moduleRepository, SessionRepository $sessionRepository, StudentRepository $studentRepository, Request $request, $key = null): Response
    {
        $key = $request->query->get('research');

        $categories = $categoryRepository->findByWord($key);
        $formateurs = $formateurRepository->findByWord($key);
        $formations = $formationRepository->findByWord($key);
        $modules = $moduleRepository->findByWord($key);
        $sessions = $sessionRepository->findByWord($key);
        $students = $studentRepository->findByWord($key);

        return $this->render('home/recherche.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'formateurs' => $formateurs,
            'formations' => $formations,
            'modules' => $modules,
            'sessions' => $sessions,
            'students' => $students
        ]);
    }
}
