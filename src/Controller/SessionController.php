<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Form\AddModuleType;
use App\Repository\SessionRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {

        $sessions = $sessionRepository->findBy([], ['id' => 'ASC']);

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
            'sessions' => $sessions
        ]);
    }

    // Method pour AJOUTER ou EDIT une SESSION
    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_EditSession(SessionRepository $sessionRepository, Session $session = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {
        // Si il n'y a pas de SESSION,
        if (!$session) {
            // On crée un nouvel objet SESSION
            $session = new Session();
        } else {
            $session = $sessionRepository->find($id);
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
            return $this->redirectToRoute('app_session');
            }
                    
        return $this->render('session/newSession.html.twig', [
            'formAddSession' => $form,
            'session' => $session,
            'edit' => $session->getId()
            ]);
    }

    #[Route('/session/{id}/addModule', name: 'new_Modulesession')]
    public function new_AddModuleSession(SessionRepository $sessionRepository, Session $session = null, Programme $programme = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {
        // Si il n'y a pas de SESSION,
        if (!$session) {
            // On redirige vers la liste des sessions
            return $this->redirectToRoute('app_session');
        } else {
            $session = $sessionRepository->find($id);
        }
            
            
        // On crée le formulaire pour la SESSION
        $form = $this->createForm(AddModuleType::class, $programme);
        
        $form->handleRequest($request);
        
        
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
            
            // On recupère les données du formulaire
            $programme = $form->getData();

            $programme->setSession($session);
            // PREPARE PDO
            $entityManager->persist($programme);
            // EXECUTE PDO
            $entityManager->flush();
            
            // Puis on redirige l'user vers la liste des SESSION
            return $this->redirectToRoute('app_session');
            }
                    
        return $this->render('session/addModule.html.twig', [
            'formAddModuleSession' => $form,
            'session' => $session,
            'programme' => $programme
            ]);
    }

    // Method pour afficher le detail d'une session
    #[Route('/session/{id}', name: 'show_session')]
    public function showSession(Session $session = null, SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository, $id): Response 
    {

        // Si la session existe, je passe à la suite, sinon je redirige sur l'index
        if ($session) {

            // Je récupère le programme de la session
            $programmes = $programmeRepository->findBy(['session' => $id]);
        
            // Et je renvoi sur le detail de la session non commencés
            return $this->render('session/showSession.html.twig', [
                'session' => $session,
                'programmes' => $programmes
            ]);

            } else {
            return $this->redirectToRoute('app_session');
        }

    }    
}
