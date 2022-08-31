<?php

namespace App\Form\Form;

use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('isPatchNote')
            ->add('description', TextareaType::class)
            ->add('img', FileType::class, [
                'label' => 'Picture (JPG file)',
                'data_class'=>null
            ])
            ->add('user', EntityType::class, ['class'=>'App\Entity\User', 'choice_label' => function($user) {
                return $user->getFirstName() .' '. $user->getLastName();
            }])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
