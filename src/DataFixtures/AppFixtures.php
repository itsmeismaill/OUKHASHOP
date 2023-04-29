<?php

namespace App\DataFixtures;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{
   private UserPasswordHasherInterface $hasher;


   public function __construct(UserPasswordHasherInterface $hasher){
    $this->hasher=$hasher;
   }
    public function load(ObjectManager $manager): void
    {
      $users = [];
      for ($i = 0; $i < 5; $i++) {
          $user = new User();
          $user->setFullName('ismail ' . $i . $i)
              ->setPseudo('itsmeismaill' . $i . $i)
              ->setEmail('ismail.oukha' . $i . $i . '@gmail.com')
              ->setRoles(['ROLE_USER'])
              ->setPlainPassword('password');
          $users[] = $user;
          $manager->persist($user);
      }
      
      $products = [];
      for ($i = 0; $i < 10; $i++) {
          $product = new Product();
          $product->setName('iphone X number' . $i)
              ->setPrice(260 + $i * 5)
              ->setImgPath('images/it geek -34.png')
              ->setDescription('Hadi description test etst ')
              ->setUser($users[1]); // Set the user property to the first User object in the $users array
          $products[] = $product;
          $manager->persist($product);
      }
      for ($i = 0; $i < 10; $i++) {
        $category = new Category();
        $category->setName('category'. $i)->setUser($users[1]); // Set the user property to the first User object in the $users array 
        $manager->persist($category);
    }
           $manager->flush();
      
    }
}
