<?php

namespace App\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductType extends AbstractType
{  private $token;

    public function __construct(TokenStorageInterface $token)
    {
       $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('name', TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('price', MoneyType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('description',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'SAVE', 'attr'=>[
                'class'=>'btn btn-primary mt-4'
            ]] )
            ->add('imgPath',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder'=>'Categories',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'query_builder'=>function(\App\Repository\CategoryRepository $r){
                    return $r->createQueryBuilder('i')
                    ->where('i.user = :user')
                    ->setParameter('user',$this->token->getToken()->getUser());
                }
                ]);
    }   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}