<?php

namespace App\Controller;

use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends AbstractController
{
    public function renderNavbar(EntityManagerInterface $entitymanager)
    {
        $cartCount = $entitymanager->getRepository(CartItem::class)->count(['user' => $this->getUser()]);
        return $this->render('inc\navbarb.html.twig', [
            'cart_count' => $cartCount,
        ]);
    }
}