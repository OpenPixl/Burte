<?php

namespace App\Form\GestApp;

use App\Entity\GestApp\Product;
use App\Entity\GestApp\ProductCategory;
use App\Entity\GestApp\ProductNature;
use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\App\Entity\Admin\member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('productFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('nature')
            ->add('category', EntityType::class, [
                'class' => ProductCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('typo', ChoiceType::class, [
                'choices'  => [
                    'Kilogramme' => 'kg',
                    'Botte' => 'botte',
                    'Douzainne' => 'douzaine',
                ],
            ])
            ->add('price')
            ->add('quantity')
            ->add('isDisponible')
            ->add('producer', EntityType::class, [
                'class' => \App\Entity\Admin\Member::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.type = :type'  )
                        ->setParameter('type', 'producteur')
                        ->orderBy('m.id', 'ASC');
                },
                'choice_label' => 'username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
