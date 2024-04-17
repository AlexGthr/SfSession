<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
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
    public function new_EditSession(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response 
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
            return $this->redirectToRoute('app_session');
            }
                    
        return $this->render('session/newSession.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId()
            ]);
    }

    // Method pour afficher le detail d'une session
    #[Route('/session/{id}', name: 'show_session')]
    public function showSession(SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository, $id): Response 
    {
        // Je vérifie qu'une session existe
        $session = $sessionRepository->find($id);

        // Si la session existe, je passe à la suite, sinon je redirige sur l'index
        if ($session) {

            // Je vérifie si la session est déjà relié à une formation, si oui elle à sa page de détail déjà prête
            $formationExist = $sessionRepository->findOneBy(['id' => $id]);
            $formation = $formationExist->getFormation() ? true : false;
            
            // Si la session à déjà une formation :
            if ($formation) {

                // Je récupère le programme de la session
                $programmes = $programmeRepository->findBy(['session' => $id]);
                $formation = true;
        
                // Et j'affiche le detail de la formation
                return $this->render('formation/showFormation.html.twig', [
                    'session' => $session,
                    'formation' => $formation,
                    'programmes' => $programmes
                ]);

            // Si la session n'as pas encore de formation
            } else {

                // Je récupère le programme de la session
                $programmes = $programmeRepository->findBy(['session' => $id]);
        
                // Et je renvoi sur le detail de la session non commencés
                return $this->render('session/showSession.html.twig', [
                    'session' => $session,
                    'programmes' => $programmes
                ]);

            }

        } else {
            return $this->redirectToRoute('app_session');
        }

    }    
}
