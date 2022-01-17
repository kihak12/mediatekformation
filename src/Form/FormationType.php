<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Niveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', null, [
                'label' => 'Date de création'
            ])
            ->add('title', null, [
                'label' => 'Titre de la formation'
            ])
            ->add('description', null, [
                'label' => 'Description de la formation'
            ])
            ->add('miniature', null, [
                'label' => 'Miniature de la vidéo (120x90)'
            ])
            ->add('picture', null, [
                'label' => 'Image de la vidéo (640x480)'
            ])
            ->add('videoId', null, [
                'label' => 'Id de la video Youtube'
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'difficulter',
                'multiple' => false,
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregister'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
