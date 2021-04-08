<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $article = $options['data'];

        $builder
            ->add('title' , TextType::class, [
                'label' => "Titre de l'article"
            ])

            ->add('content', TextareaType::class,[
                'label' => "Contenu de l'article"
            ])
            
            ->add('image', FileType::class, [
                    'label' => 'Insérer une image',
                    'mapped' => false,
                    'required' => false,
            ])

            ->add('submit', SubmitType::class, [
                     'label' => $article->getId() ? 'Envoyer l\'article' : 'Ajouter l\'article'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
