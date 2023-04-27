<?php

namespace App\DataFixtures;
use App\Entity\Product;
use App\Entity\User;
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
//         for ($i=0; $i <10 ; $i++) { 
//             # code...
        
//          $product = new Product();
//         $product->setName('iphone X number'.$i)->setprice(260+$i*5)->setImgPath('images/it geek -34.png')->setDescription('Hadi description test etst ');
//         $manager->persist($product);
// }
         //users
        for ($i=0; $i <5 ; $i++){
          $user=new User();
          $user->setFullName('ismail '.$i)
            ->setPseudo('itsmeismaill' .$i)
            ->setEmail('ismail.oukha'.$i.'@gmail.com')
            ->setRoles(['ROLE USER'])
            ->setPlainPassword('password');
            $manager->persist($user);

      }
        $manager->flush();
    }
}
