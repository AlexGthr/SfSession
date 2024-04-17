<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository): Response
    {

        $modules = $moduleRepository->findBy([], ['name' => 'ASC']);

        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'modules' => $modules
        ]);
    }

    // Method pour afficher le detail d'un module
    #[Route('/module/{id}', name: 'show_module')]
    public function showModule(ModuleRepository $moduleRepository, $id): Response 
    {
        $module = $moduleRepository->find($id);

        if ($module) {

        return $this->render('module/showModule.html.twig', [
            'module' => $module
        ]);

        } else {
            return $this->redirectToRoute('app_module');
    }
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
            return $this->redirectToRoute('app_module');
            }
                    
        return $this->render('module/newModule.html.twig', [
            'formAddModule' => $form,
            'edit' => $module->getId()
            ]);
    }
}
