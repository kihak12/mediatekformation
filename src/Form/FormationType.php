<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
