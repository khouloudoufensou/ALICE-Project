<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $Formation=$options['data'];

        $builder
            ->add('title', TextType::class,[
                'label' => "Ajoutez un titre"
            ] )
            ->add('description',TextType::class,[
                'label' => "Ajoutez une description de la forrmation"
            ] )
            ->add('program',TextType::class,[
                'label' => "Ajoutez le detail du programme"
            ] )
            ->add('period',TextType::class,[
                'label' => "Ajoutez la durée de la formation"
            ] )
            ->add('price',IntegerType::class,[
                'label' => "Ajoutez un prix"
            ] )
            ->add('startAt',DateType::class,[
                'label' => "Ajoutez la date de debut de la formation"
            ])
            ->add('speaker',TextType::class,[
                'label' => "Ajoutez le nom et prenom de l'intervenant"
            ] )
            ->add('presSpeaker',TextType::class,[
                'label' => "Ajoutez une présentation de l'intervenant"
            ] )
            ->add('seatingCapacity', IntegerType::class,[
                'label'=>"Ajoutez le nombre de place"
            ] )
            ->add('picture', FileType::class,[
                'label' => "Ajoutez une image" ,
                'mapped' => false,
                'required' => false,
                
            ])
            
            ->add('submit', SubmitType::class, [
                'label'=>$Formation->getId()?'Modifier': 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
