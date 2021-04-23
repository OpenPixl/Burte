<?php

namespace App\Form\Admin;

use App\Entity\Admin\member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class memberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('adress1')
            ->add('Adress2')
            ->add('zipcode')
            ->add('city')
            ->add('phoneDesk')
            ->add('phoneGsm')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => member::class,
        ]);
    }
}
