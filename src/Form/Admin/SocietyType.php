<?php

namespace App\Form\Admin;

use App\Entity\Admin\Society;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocietyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('respFirstname')
            ->add('respLastName')
            ->add('address')
            ->add('complement')
            ->add('zipcode')
            ->add('city')
            ->add('siret')
            ->add('ape')
            ->add('urlweb')
            ->add('isRs')
            ->add('urlFacebook')
            ->add('urlInstagram')
            ->add('urlLinkedin')
            ->add('isGroupeEntreprise')
            ->add('FirstActivity')
            ->add('secondActivity')
            ->add('projectDev')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Society::class,
        ]);
    }
}
