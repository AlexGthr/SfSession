<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/module/{id}', name: 'app_apiModule')]
    public function findModule(ModuleRepository $moduleRepository, $id = null): Response
    {

        $modulesObject = $moduleRepository->findModuleNotUse($id);

        $modules = [];
        foreach ($modulesObject as $module) {
            
            $modules[] = [
                'id' => $module->getId(),
                'name' => $module->getName()
            ];

        }

        return new JsonResponse([
            'modules' => $modules,
        ]);
    }

    #[Route('/api/stagiaires/{id}', name: 'app_apiStagiaire')]
    public function findStagiaire(SessionRepository $sessionRepository, $id = null): Response
    {

        $stagiaireObject = $sessionRepository->findStagaireNonInscrit($id);

        $stagiaires = [];
        foreach ($stagiaireObject as $stagiaire) {
            
            $stagiaires[] = [
                'id' => $stagiaire->getId(),
                'name' => $stagiaire->getName(),
                'lastName' => $stagiaire->getLastName(),
            ];

        }

        return new JsonResponse([
            'stagiaires' => $stagiaires,
        ]);
    }
}
