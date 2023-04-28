<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlenght'=>'2',
                    'maxlenght'=>'50'
                ],
                'label'=>'Nom / Prenom ',
                'label_attr'=>[
                    'class'=>'form_label'
                ],
                'constraints'=>[
                 new Assert\NotBlank(),
                 new Assert\Length(['min'=>2 , 'max'=>50 ])
                ]
            ])
            ->add('pseudo',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlenght'=>'2',
                    'maxlenght'=>'50'
                ],
                'label'=>'Pseudo (Facultatif)',
                'label_attr'=>[
                    'class'=>'form_label'
                ],
                'constraints'=>[
                 new Assert\Length(['min'=>2 , 'max'=>50 ])
                ]
            ])
            ->add('email', EmailType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlenght'=>'2',
                    'maxlenght'=>'180'
                ],
                'label'=>'Email',
                'label_attr'=>[
                    'class'=>'form_label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Email(),
                 new Assert\Length(['min'=>2 , 'max'=>180])
                ]
            ])
            ->add('plainPassword',RepeatedType::class,[
                'type'=>PasswordType::class,
                'first_options'=>[
                  'attr'=>[
                    'class'=>'form-control'
                  ],
                  'label'=>'Mot de passe',
                  'label_attr'=>[
                    'class'=>'form-label'
                  ]
                ],
                'second_options'=>[
                    'attr'=>[
                        'class'=>'form-control'
                      ],
                      'label'=>'Mot de passe',
                      'label_attr'=>[
                        'class'=>'form-label'
                      ]
                ],
                'invalid_message'=>'les mots de passes ne se correspondent pas.'
            ])
            ->add('Submit',SubmitType::Class,[
                'attr'=>[
                    'class'=>'btn btn-dark'
                ]
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
