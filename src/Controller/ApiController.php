<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/module/', name: 'app_apiModule')]
    public function findModule(ModuleRepository $moduleRepository, $id = 1): Response
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
}
