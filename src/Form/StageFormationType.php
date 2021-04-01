<?php

namespace App\Form;

use App\Entity\StageFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $stageFormation=$options['data'];
        $builder
            ->add('titre')
            ->add('prix')
            ->add('detailprogram')
            ->add('intervenant')
            ->add('presintervenant')
            ->add('date')
            ->add('duree')
            ->add('description')
            ->add('nbrplace')

            ->add('submit', SubmitType::class, [
                'label'=>$stageFormation->getId()?'Modifier': 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StageFormation::class,
        ]);
    }
}
