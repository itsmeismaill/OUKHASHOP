<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(EntityManagerInterface $entitymanager): Response
    {
        $cartItems = $entitymanager->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        if (!$cartItems) {
//            throw $this->createNotFoundException(
//                'You have no product in your cart !'
//            );
        }

        return $this->render('cart\index.html.twig', [
            'cart_items' => $cartItems,
            'controller_name' => 'CartController'
        ]);
    }

    // add to cart by quantity by form
    #[Route('/cart/add', name: 'add_to_cart')]
    public function add(EntityManagerInterface $entitymanager, Request $request): Response
    {
        $cartItem = new CartItem();

        $product_id = $request->get('product_id');
        $quantity = $request->get('quantity');

        $product = $entitymanager->getRepository(Product::class)->find($product_id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $product_id
            );
        }

        $cartItem->setProduct($product);
        $cartItem->setUser($this->getUser());
        $cartItem->setQuantity($quantity);

        $entitymanager->persist($cartItem);
        $entitymanager->flush();
        $this->addFlash(
            'success',
            'The product ' . $product->getName() . ' was added successfully to your cart!'
        );
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete(EntityManagerInterface $entitymanager, $id): Response
    {
        $cartItem = $entitymanager->getRepository(CartItem::class)->find($id);
        if (!$cartItem) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $entitymanager->remove($cartItem);
        $entitymanager->flush();
        $this->addFlash(
            'success',
            'The product ' . $cartItem->getProduct()->getName() . ' was deleted successfully from your cart!'
        );
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/empty', name: 'app_cart_deleteall')]
    public function deleteall(EntityManagerInterface $entitymanager): Response
    {
        $cartItems = $entitymanager->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        if (!$cartItems) {
//            throw $this->createNotFoundException(
//                'You have no product in your cart !'
//            );
        }
        foreach ($cartItems as $cartItem) {
            $entitymanager->remove($cartItem);
        }
        $entitymanager->flush();
        $this->addFlash(
            'success',
            'All products were deleted successfully from your cart!'
        );
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/checkout', name: 'app_cart_checkout')]
    public function checkout(EntityManagerInterface $entitymanager): Response
    {
        $cartItems = $entitymanager->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        if (!$cartItems) {
//            throw $this->createNotFoundException(
//                'You have no product in your cart !'
//            );
        }

        // add to purchase
        foreach ($cartItems as $cartItem) {
            $purchase = new Purchase();
            $purchase->setUser($this->getUser());
            $purchase->setProduct($cartItem->getProduct());
            $purchase->setQuantity($cartItem->getQuantity());
            $purchase->setDate(new \DateTime());
            $entitymanager->persist($purchase);
        }

        foreach ($cartItems as $cartItem) {
            $entitymanager->remove($cartItem);
        }
        $entitymanager->flush();
        $this->addFlash(
            'success',
            'All products were deleted successfully from your cart!'
        );
        return $this->redirectToRoute('app_cart');
    }
}