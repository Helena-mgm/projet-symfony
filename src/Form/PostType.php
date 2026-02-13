<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('title')
    ->add('content')
    ->add('picture', FileType::class, [
        'label' => 'Image de couverture',
        'mapped' => false,
        'required' => false,
    ])
    ->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => 'name',
        'placeholder' => 'Choisir une catégorie',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
