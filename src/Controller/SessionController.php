<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\AddModuleType;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
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
    public function new_AddModuleSession(SessionRepository $sessionRepository, Session $session = null, ModuleRepository $moduleRepository, Programme $programme = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {
        // Si il n'y a pas de SESSION,
        if (!$session) {
            // On redirige vers la liste des sessions
            return $this->redirectToRoute('app_session');
        } else {
            $session = $sessionRepository->find($id);
        }
            


        // Si le formulaire est submit
        if ($_POST['submit']) {
            
            $nbDay = filter_input(INPUT_POST, "nbDay", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $module = filter_input(INPUT_POST, "module", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nbDay && $module) {

                $moduleId = $moduleRepository->findOneById($module);
                $programme = new Programme();

                $programme->setnbDay($nbDay);
                $programme->setModule($moduleId);
                $programme->setSession($session);

                // PREPARE PDO
                $entityManager->persist($programme);
                // EXECUTE PDO
                $entityManager->flush();

                // Puis on redirige l'user vers la liste des SESSION
                return $this->redirectToRoute('show_session', ['id' => $id]);
            } else {
                return $this->redirectToRoute('show_session', ['id' => $id]);
            }
            }
                    
        return $this->render('session/addModule.html.twig', [
            'formAddModuleSession' => $form,
            'session' => $session,
            'programme' => $programme
            ]);
    }

    #[Route('/session/{id}/addStagiaire', name: 'new_stagiaireSession')]
    public function new_AddStudentSession(SessionRepository $sessionRepository, Session $session = null, StudentRepository $studentRepository, Programme $programme = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {
        // Si il n'y a pas de SESSION,
        if (!$session) {
            // On redirige vers la liste des sessions
            return $this->redirectToRoute('app_session');
        } else {
            $session = $sessionRepository->find($id);
        }
            


        // Si le formulaire est submit
        if ($_POST['submit']) {
            
            $stagiaire = filter_input(INPUT_POST, "stagiaire", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($stagiaire) {

            $stagiaireId = $studentRepository->findOneById($stagiaire);

            // Définissez la session et le stagiaire pour cette instance
            $session->addInscription($stagiaireId);

            // Enregistrez cette nouvelle instance dans la base de données
            $entityManager->persist($session);
            $entityManager->flush();

    
             // Puis on redirige l'utilisateur vers la liste des SESSION
            return $this->redirectToRoute('show_session', ['id' => $id]);
            } else {
                return $this->redirectToRoute('show_session', ['id' => $id]);
            }
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
            $stagiaireNonInscrit = $sessionRepository->findStagaireNonInscrit($id);
        
            // Et je renvoi sur le detail de la session non commencés
            return $this->render('session/showSession.html.twig', [
                'session' => $session,
                'programmes' => $programmes,
                'stagiaireNonInscrit' => $stagiaireNonInscrit
            ]);

            } else {
            return $this->redirectToRoute('app_session');
        }

    }

    // Method pour supprimée un stagiaire d'une session
    #[Route('/session/{sessionId}/stagiaire/{studentId}/delete', name: 'del_StudentSession')]
    public function delStudentSession(Session $session = null, SessionRepository $sessionRepository, Student $student = null, StudentRepository $studentRepository, $sessionId = null, $studentId = null, EntityManagerInterface $entityManager): Response 
    {

        $session = $sessionRepository->findOneById($sessionId);
        $student = $studentRepository->findOneById($studentId);

        if ($session && $student) {

            // Je récupère le programme de la session
            
            $delete = $session->removeInscription($student);

            $entityManager->persist($delete);
            $entityManager->flush();
        
            return $this->redirectToRoute('show_session', ['id' => $sessionId]);

            } else {
            return $this->redirectToRoute('app_session');
        }

    }
    
        // Method pour supprimée un stagiaire d'une session
        #[Route('/session/{sessionId}/programme/{programmeId}/delete', name: 'del_ModuleSession')]
        public function delModuleSession(Session $session = null, SessionRepository $sessionRepository, Programme $programme = null, ProgrammeRepository $programmeRepository, $sessionId = null, $programmeId = null, EntityManagerInterface $entityManager): Response 
        {
    
            $session = $sessionRepository->findOneById($sessionId);
            $programme = $programmeRepository->findOneById($programmeId);
    
            if ($session && $programme) {
    
                // Je récupère le programme de la session
                
                $delete = $session->removeProgramme($programme);
    
                $entityManager->persist($delete);
                $entityManager->flush();
            
                return $this->redirectToRoute('show_session', ['id' => $sessionId]);
    
                } else {
                return $this->redirectToRoute('app_session');
            }
    
        } 
}
