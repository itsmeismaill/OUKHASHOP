<?php

namespace App\Controller;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ProductController extends AbstractController
{   
    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entitymanager): Response
    {   
        $products=$entitymanager->getRepository(Product::class)->findBy(['user'=>$this->getUser()]);
        if (!$products) {
            throw $this->createNotFoundException(
                'No product found in the our DATABASE !'
            );
        }

        return $this->render('product\index.html.twig', [
            'products' => $products,
            'controller_name' => 'ProductController'
        ]);

    }
    #[Route('/product/new', name:'new_product', methods:['GET','POST'])]
 public function new(Request $request,EntityManagerInterface $entityManager) {
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $product = $form->getData();
    $product->setUser($this->getUser());
    $entityManager->persist($product);
    $entityManager->flush();
    $this->addFlash(
        'success',
        'The product '.$product->getname().' was Created successfully!'
     );
   
    return $this->redirectToRoute('app_product');
    }
    return $this->render('product/new.html.twig',['form' => $form->createView()]);
    }
    #[Route('/product/edit/{id}', name:'edit_product', methods:['GET','POST'])]
 public function edit(Request $request,EntityManagerInterface $entityManager,Product $product) {
    $form = $this->createForm(ProductType::class,$product);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $product = $form->getData();
    $product->setUser($this->getUser());
    $entityManager->persist($product);
    $entityManager->flush();
    $this->addFlash(
        'edited',
        'The product '.$product->getname().' was Edited successfully!'
     );
   
    return $this->redirectToRoute('app_product');
    }
    return $this->render('product/new.html.twig',['form' => $form->createView()]);
    }
    #[Route('/product/delete/{id}', name: 'product_delete' , methods:['GET','POST'])]
    public function deleteP(Product $product,EntityManagerInterface $entityManager){

         
        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash(
            'danger',
            'The product '.$product->getname().' was Deleted successfully!'
         );
        return $this->redirectToRoute('app_product');  
      }
      #[Route('/home', name: 'home')]
    public function Home(): Response
    {   

        return $this->render('home.html.twig');

   
}
#[Route('/product/details/{id}', name: 'details')]
public function details(Product $product): Response
{   

    return $this->render('product\details.html.twig',[
        'product'=>$product,
    ]);


}
// #[Route('/', name: 'home')]
// public function home1(): Response
// {   

//     return $this->render('home.html.twig');
// }
}
