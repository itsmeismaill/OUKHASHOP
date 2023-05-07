<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entitymanager): Response
    {   
        $categories=$entitymanager->getRepository(Category::class)->findBy(['user'=>$this->getUser()]);

        return $this->render('category\indexcat.html.twig', [
            'categories' => $categories,
            'controller_name' => 'CategoryController'
        ]);

    }

    #[Route('/category/newcat', name: 'new_category', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setUser($this->getUser());
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'The Category ' . $category->getname() . ' was Created successfully!'
            );

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/newcat.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/category/edit/{id}', name: 'edit_category', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Category $category)
    {

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash(
                'edited',
                'The category ' . $category->getname() . ' was Edited successfully!'
            );

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/newcat.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/category/delete/{id}', name: 'category_delete', methods: ['GET', 'POST'])]
    public function deleteP(Category $category, EntityManagerInterface $entityManager)
    {


        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash(
            'danger',
            'The Category ' . $category->getname() . ' was Deleted successfully!'
        );
        return $this->redirectToRoute('app_category');
    }

    #[Route('/category/{id}')]
    public function index2(EntityManagerInterface $entitymanager, Category $category, int $id): Response
    {
        $category = $entitymanager->getRepository(Category::class)->find($id);
        $products = $entitymanager->getRepository(Product::class)->findAll();
        if (!$products) {
            throw $this->createNotFoundException(
                'No product found in the our DATABASE !'
            );
        }

        return $this->render('category\index2.html.twig', [
            'products' => $category->getProduct()
        ]);

    }

}
