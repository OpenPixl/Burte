<?php

namespace App\Form\Admin;

use App\Entity\Admin\Structure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class StructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('respFirstName')
            ->add('respLastName')
            ->add('address')
            ->add('complement')
            ->add('zipcode')
            ->add('city')
            ->add('siret')
            ->add('ape')
            ->add('tvaNumber')
            ->add('urlWeb')
            ->add('urlFacebook')
            ->add('urlInstagram')
            ->add('urlLinkedin')
            ->add('EmailStruct')
            ->add('phoneDesk')
            ->add('phoneGsm')
            ->add('FirstActivity')
            ->add('secondActivity')
            ->add('projectDev')
            ->add('logoStructureFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('jaf', ChoiceType::class, [
                'choices'  => [
                    'jaf-lons' => 'JUST à FAIRE - Lons',
                    'jaf-capbreton' => 'JUST à FAIRE - Capbreton',
                    'jaf-anglet' => 'JUST à FAIRE - anglet',
                    'jaf-mdm' => 'JUST à FAIRE - Mont de Marsan',
                ],
            ])
            ->add('illustrationFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('illustrationtwoFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('illustrationthirdFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}
