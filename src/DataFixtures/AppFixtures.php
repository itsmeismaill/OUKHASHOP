<?php

namespace App\DataFixtures;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i <10 ; $i++) { 
            # code...
        
         $product = new Product();
        $product->setName('iphone X number'.$i)->setprice(260+$i*5)->setImgPath('images/it geek -34.png')->setDescription('Hadi description test etst ');
        $manager->persist($product);
}
        $manager->flush();
    }
}
