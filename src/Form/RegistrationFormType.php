<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'class'=>'form__field',
                    'placeholder'=>'Prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'class'=>'form__field',
                    'placeholder'=>'Nom'
                ]
            ])

            ->add('email', EmailType::class,[
                'label' => false,
                'attr' => [
                    'class'=>'form__field',
                    'placeholder'=>'E-mail'
                ]
            ])

            ->add('plainPassword', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class'=>'form__field',
                    'placeholder'=>'Mot de passe'
                ],                
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                        'maxMessage' => 'Le mot de passe doit faire maximum {{ limit }} caractères',
                    ]),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'label' =>"J'accèpte les <a href='#' data-bs-toggle='modal' data-bs-target='#termsModel'> conditions génerale d'utilisation </a> ",
                'label_html' => true, 
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les conditions générales.',
                    ]),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Inscription",
                'attr' => [
                    'class'=>'form__button form__submit'
                ], 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
