<?php

namespace App\Form\Gestapp;

use App\Entity\Admin\Member;
use App\Entity\Gestapp\Recommandation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Recommandation2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('clientFirstname')
            ->add('clientLastname')
            ->add('clientAddress')
            ->add('clientComplement')
            ->add('clientZipcode')
            ->add('clientCity')
            ->add('clientEmail')
            ->add('clientDesk')
            ->add('clientGsm')
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.id', 'ASC');
                },
                'choice_label' => 'username',
            ])
            ->add('recoState', ChoiceType::class, [
                'choices'  => [
                    'non lue' => 'noRead',
                    'En cours' => 'inProgress',
                    'Validée' => 'Valid',
                    'Sans suite' => 'Avort'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recommandation::class,
        ]);
    }
}
