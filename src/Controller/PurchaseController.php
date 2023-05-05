<?php

namespace App\Controller;

use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/purchases', name: 'app_purchase')]
    public function index(EntityManagerInterface $entitymanager): Response
    {
        $purchases = $entitymanager->getRepository(Purchase::class)->findBy(['user' => $this->getUser()]);
        if (!$purchases) {
//            throw $this->createNotFoundException(
//                'You have no purchase !'
//            );
        }

        return $this->render('purchase\index.html.twig', [
            'purchases' => $purchases,
            'controller_name' => 'PurchaseController'
        ]);
    }

    #[Route('/purchase/delete/{id}', name: 'delete_purchase')]
    public function delete(EntityManagerInterface $entitymanager, $id): Response
    {
        $purchase = $entitymanager->getRepository(Purchase::class)->find($id);
        if (!$purchase) {
//            throw $this->createNotFoundException(
//                'No purchase found for id ' . $id
//            );
        }
        $entitymanager->remove($purchase);
        $entitymanager->flush();
        $this->addFlash(
            'success',
            'The purchase ' . $purchase->getId() . ' was deleted successfully!'
        );
        return $this->redirectToRoute('app_purchase');
    }

    // delete all
    #[Route('/purchase/delete', name: 'delete_all_purchase')]
    public function deleteAll(EntityManagerInterface $entitymanager): Response
    {
        $purchases = $entitymanager->getRepository(Purchase::class)->findBy(['user' => $this->getUser()]);
        if (!$purchases) {
//            throw $this->createNotFoundException(
        }
        foreach ($purchases as $purchase) {
            $entitymanager->remove($purchase);
        }
        $entitymanager->flush();
        $this->addFlash(
            'success',
            'All purchases were deleted successfully!'
        );
        return $this->redirectToRoute('app_purchase');
    }
}
