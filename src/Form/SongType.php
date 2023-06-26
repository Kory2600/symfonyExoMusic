<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Genre;
use App\Entity\Song;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('createdAt', DateTimeType::class,[
                'widget' => 'single_text'
            ])
            ->add('duration')
            ->add('Users', EntityType::class, [
            'class' => User::class,
            'choice_label' => function($users){
                return $users->getPrenom(). ' '.$users->getNom();
            }
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'label',
                'expanded' => false
                ])
            ->add('albums', EntityType::class, [
                'class' => Album::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                ])
          //  ->add('genre')
          //  ->add('albums') //
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
