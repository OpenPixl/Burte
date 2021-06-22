<?php

namespace App\Form\GestApp;

use App\Entity\GestApp\Adhesion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdhesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('society')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('email')
            ->add('siret')
            ->add('gerant')
            ->add('urlWeb')
            ->add('urlFacebook')
            ->add('urlInstagram')
            ->add('urlLinkedin')
            ->add('isGroupeEntreprendre')
            ->add('firstActivity')
            ->add('secondActivity')
            ->add('projectDev')
            ->add('phoneDesk')
            ->add('phoneGsm')
            ->add('isConsentRgpd')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class,
        ]);
    }
}
