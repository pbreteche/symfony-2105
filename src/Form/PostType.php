<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body')
        ;

        if ($options['with_author']) {
            $builder
                ->add('writtenBy', EntityType::class, [
                    'class' => Author::class,
                    'choice_label' => 'name',
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'with_author' => true,
        ]);
    }
}
