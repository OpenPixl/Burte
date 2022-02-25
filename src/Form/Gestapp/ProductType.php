<?php

namespace App\Form\Gestapp;

use App\Entity\Gestapp\Product;
use App\Entity\Gestapp\ProductCategory;
use App\Entity\Gestapp\ProductNature;
use App\Repository\Gestapp\ProductCategoryRepository;
use App\Repository\GestApp\ProductNatureRepository;
use App\Entity\Gestapp\ProductUnit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(['message' => 'veuillez entrer un nom de produit.'])
            ])
            ->add('ref')
            ->add('description')
            ->add('details')
            ->add('productFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télecharger',
            ])
            ->add('productNature', EntityType::class, [
                'placeholder' => 'Choisir une nature',
                'class' => ProductNature::class,
                'choice_label' => 'name',
                'query_builder' => function (ProductNatureRepository $productNatureRepository) {
                    return $productNatureRepository->createQueryBuilder('pn')->orderBy('pn.name', 'ASC');
                },

            ])
            ->add('productCategory', EntityType::class, [
                'placeholder' => 'Choisir une nature',
                'class' => ProductCategory::class,
                'choice_label' => 'name',
                'query_builder' => function (ProductNatureRepository $productNatureRepository) {
                    return $productNatureRepository->createQueryBuilder('pc')->orderBy('pc.name', 'ASC');
                },

            ])
            ->add('productUnit', EntityType::class, [
                'placeholder' => 'Choisir une unité de tarif',
                'required' => false,
                'class' => ProductUnit::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('price')
            ->add('quantity')
            ->add('isDisponible')
            ->add('producer', EntityType::class, [
                'class' => \App\Entity\Admin\Member::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.type = :type')
                        ->setParameter('type', 'producteur')
                        ->orderBy('m.id', 'ASC');
                },
                'choice_label' => 'structure',
            ])
            ->add('tva', ChoiceType::class, [
                'choices'  => [
                    'TVA 20%' => "20",
                    'TVA 19,6%' => '19.6',
                    'TVA 5,5%' => '5.5',
                    'TVA 2.1%' => '2.1',
                    'TVA 0%' => '0',
                ],
            ])
            ->add('format', ChoiceType::class, [
                'choices'  => [
                    'Carte 10*15' => "card10-15",
                    'Carte double' => 'card_double',
                    'Autres' => 'other',
                ],
            ])
            ->add('isPersonalisable')
            ->add('otherCategory', CollectionType::class, [
            ])
        ;

        $formModifier = function(FormInterface $form, ProductNature $productNature = null){
            $category = (null === $productNature) ? [] : $productNature->getProductCategories();
            $form->add('productCategory', EntityType::class, [
                'class' => ProductCategory::class,
                'choices' => $category,
                'choice_label' => 'name',
                'placeholder' => 'Choisir la category',
                'label' => 'Catégorie'
            ]);
        };

        $builder->get('productNature')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){
                $nature = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $nature);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
