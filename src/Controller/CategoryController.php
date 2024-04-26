<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ModuleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();

        if ($user) {

        $categorys = $categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categorys' => $categorys
        ]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour AJOUTER ou EDIT une CATEGORIE
    #[Route('/category/new', name: 'new_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]
    public function new_editCategory(CategoryRepository $categoryRepository, Category $category = null, Request $request, EntityManagerInterface $entityManager, $id = null): Response 
    {

        $user = $this->getUser();

        if ($user) {
        // Si il n'y a pas CATEGORIE,
        if (!$category) {
            // On crée un nouvel objet CATEGORIE
            $category = new Category();
        } else {
            $category = $categoryRepository->find($id);
        }


        // On crée le formulaire pour la CATEGORIE
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        
        // Si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
            
            // On recupère les données du formulaire
            $category = $form->getData();

            // PREPARE PDO
            $entityManager->persist($category);
            // EXECUTE PDO
            $entityManager->flush();

            // Puis on redirige l'user vers la liste des CATEGORIE
            $flashy->info("Formulaire validée.", "");
            return $this->redirectToRoute('app_category');
        }
        
        return $this->render('category/newCateg.html.twig', [
            'formAddCategory' => $form,
            'category' => $category,
            'edit' => $category->getId()
        ]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Method pour supprimer une catégorie
    #[Route('/category/{id}/delete', name: 'del_category')]
        public function deleteCateg(Category $category = null, ModuleRepository $moduleRepository,EntityManagerInterface $entityManager, $id = null): Response
    {
        
        $user = $this->getUser();

        if ($user) {

        if (!$category) {
            return $this->redirectToRoute('app_category');
        }

        $moduleHaveCateg = $moduleRepository->findOneBy(['category' => $id]);

        if ($moduleHaveCateg) {

            return $this->redirectToRoute('app_category');

        } else {
            // Permet la suppression d'une catégorie (delete from)
            $entityManager->remove($category);
            $entityManager->flush();

            // Puis on redirige l'user vers la liste des catégories
            return $this->redirectToRoute('app_category');
        }

        }
        else {
            return $this->redirectToRoute('app_login');
        }

    }
}
