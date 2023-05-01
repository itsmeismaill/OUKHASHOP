<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
Use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class SecurityController extends AbstractController
{
    
    #[Route('/', name: 'security.login', methods:['GET','POST'])]
     public function login(AuthenticationUtils $authenticationUtils): Response
    { 
            // if user is already authenticated, redirect to home page
          if ($this->getUser()) {
            $this->addFlash(
                'success',
                'You Are Connected Successfully!'
             );
             return $this->redirectToRoute('home');
    }
        // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('/security/Login.html.twig', [
             'last_username' => $lastUsername,
             'error'         => $error,
        ]);
    }
    #[Route('/deconnexion', name: 'security.logout', methods:['GET','POST'])] 
    public function logout()
    {
        return $this->redirectToRoute('security.login');
    }
    #[Route('/inscription', name: 'security.registration', methods:['GET','POST'])]
    public function registration(Request $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager):Response
    {
        $user= new User();
        $form= $this->createForm(RegistrationType::Class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
           
            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'The user '.$user->getFullName().' was Created successfully!'
             );
             return $this->redirectToRoute('security.login');
            }
        return $this->render('security/registration.html.twig',[
            'form'=> $form->createView()
        ]);
    
}
}