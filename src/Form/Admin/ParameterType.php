<?php

namespace App\Form\Admin;

use App\Entity\Admin\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameSite')
            ->add('sloganSite')
            ->add('descrSite')
            ->add('isOnline')
            ->add('adminEmail')
            ->add('adminWebmaster')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }
}
