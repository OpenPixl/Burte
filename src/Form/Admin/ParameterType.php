<?php

namespace App\Form\Admin;

use App\Entity\Admin\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameSite')
            ->add('sloganSite')
            ->add('descrSite')
            ->add('isOnline')
            ->add('adminEmail', EmailType::class)
            ->add('adminWebmaster')
            ->add('isBlocMenuFluid')
            ->add('logoFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('faviconFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('offlMessage')
            ->add('isShowOfflineLogo')
            ->add('isShowOfflineMessage')
            ->add('urlFacebook')
            ->add('urlInstagram')
            ->add('urlLinkedin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }
}
