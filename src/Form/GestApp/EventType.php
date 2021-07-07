<?php

namespace App\Form\GestApp;

use App\Entity\GestApp\Event;
use App\Entity\GestApp\EventGal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('isPublish')
            ->add('isValidBy')
            ->add('eventGals', CollectionType::class, [
                'entry_type' => EventGalType::class,
                'allow_add' => true, // true si tu veux que l'utilisateur puisse en ajouter
                'allow_delete' => true, // true si tu veux que l'utilisateur puisse en supprimer
                'label' => 'Photos',
                'by_reference' => false, // voir  https://symfony.com/doc/current/reference/forms/types
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
