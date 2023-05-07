<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entitymanager): Response
    {
        $products = $entitymanager->getRepository(Product::class)->findBy(['user' => $this->getUser()]);

        return $this->render('product\index.html.twig', [
            'products' => $products,
            'controller_name' => 'ProductController'
        ]);

    }

    #[Route('/productb', name: 'app_productb')]
    public function indexb(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();
        $productsWithUser = [];
        foreach ($products as $product) {
            $user = $product->getUser();
            $pseudo = $user ? $user->getPseudo() : 'N/A';
            $productsWithUser[] = [
                'product' => $product,
                'pseudo' => $pseudo,
            ];
        }

        return $this->render('product/indexb.html.twig', [
            'productsWithUser' => $productsWithUser,
            'controller_name' => 'ProductController'
        ]);
    }

    #[Route('/product/new', name: 'new_product', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                        $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
        
                        // Move the file to the directory where brochures are stored
                        try {
                            $brochureFile->move(
                                $this->getParameter('brochures_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            $this->addFlash(
                                'danger',
                                'file not accepted!'
                             );
                        }
        
                        // updates the 'brochureFilename' property to store the PDF file name
                        // instead of its contents
                        $product->setBrochureFilename($newFilename);
                    }
    $product = $form->getData();
    $product->setUser($this->getUser());
    $entityManager->persist($product);
    $entityManager->flush();
            $this->addFlash(
                'success',
                'The product ' . $product->getname() . ' was Created successfully!'
            );

            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/new.html.twig', ['form' => $form->createView()]);
    }

//    #[Route('/product/edit/{id}', name: 'edit_product', methods: ['GET', 'POST'])]
//    public function edit(Request $request, EntityManagerInterface $entityManager, Product $product)
//    {
//        $form = $this->createForm(ProductType::class, $product);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $product = $form->getData();
//            $product->setUser($this->getUser());
//            $entityManager->persist($product);
//            $entityManager->flush();
//            $this->addFlash(
//                'edited',
//                'The product ' . $product->getname() . ' was Edited successfully!'
//            );
//
//            return $this->redirectToRoute('app_product');
//
//        }
//    }

    #[Route('/product/edit/{id}', name: 'edit_product', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setUser($this->getUser());
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash(
                'edited',
                'The product ' . $product->getname() . ' was Edited successfully!'
            );

            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/product/delete/{id}', name: 'product_delete', methods: ['GET', 'POST'])]
    public function deleteP(Product $product, EntityManagerInterface $entityManager)
    {


        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash(
            'danger',
            'The product ' . $product->getname() . ' was Deleted successfully!'
        );
        return $this->redirectToRoute('app_product');
    }

    #[Route('/home', name: 'home')]
    public function Home(): Response
    {

        return $this->render('home.html.twig');


    }

    #[Route('/home2', name: 'home2')]
    public function Home2(): Response
    {

        return $this->render('home2.html.twig');


    }

    #[Route('/product/details/{id}', name: 'details')]
    public function details(Product $product): Response
    {
        return $this->render('product\details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/detailsb/{id}', name: 'detailsb')]
    public function detailsb(Product $product): Response
    {

        return $this->render('product\detailsb.html.twig', [
            'product' => $product,
        ]);


    }
// #[Route('/cart/add/{id}', name: 'cart_add')]
// public function addToCart(Product $product, CartService $cartService): Response
// {
//     $cartService->add($product);

//     $this->addFlash(
//         'success',
//         'The product '.$product->getName().' was added to your cart!'
//     );

//     return $this->redirectToRoute('app_product2');
// }

}