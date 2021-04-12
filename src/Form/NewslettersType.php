<?php

namespace App\Form;

use App\Entity\Newsletters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewslettersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                    $builder
                        ->add('email', EmailType::class, [
                            'attr' => [
                                'placeholder' => 'Votre email',
                                'class'=>'form__field'

                                ]
                            ]
                        )
                    ;
                }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Newsletters::class,
        ]);
    }
}
