<?php

namespace App\Form\Webapp;

use App\Entity\Gestapp\ProductCategory;
use App\Entity\Webapp\Article;
use App\Entity\Webapp\Section;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('attrId')
            ->add('attrName')
            ->add('attrClass')
            ->add('page')
            ->add('contentType', ChoiceType::class, [
                'choices'  => [
                    'aucun' => 'none',
                    'ARTICLES' => [
                        'Un article' => 'One_article',
                        'Plusieurs articles' => 'More_article',
                        'Une categorie' => 'Category',
                    ],
                    'EVENEMENTS' =>[
                        'Un évènement' => 'One_event',
                        'les évènements' => 'Events',
                        'historiques des évènements' => 'HistoryOfEvent',
                    ],
                    'ECOMMERCE' =>[
                        "Listes des produits" => "All_products",
                        "Une catégorie de produit" => "One_Cat_Product",
                    ],
                    'MEMBRES' => [
                        'membre' => 'Member',
                        "bulletin d'adhésion" => "Adhesion",
                    ],
                    'ANIMATION' => [
                        'Compteur' => "CountUp"
                    ],
                    'DIVERS' => [
                        'introduction' => 'intro',
                        "liste des avis" => "Avis",
                        'Autres' => 'Others'
                    ],
                ],
            ])
            ->add('oneArticle', EntityType::class, [
                'class' => Article::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.title', 'ASC');
                },
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-select form-select-sm',
                ]
            ])
            ->add('oneCatproduct', EntityType::class, [
                'class' => ProductCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('favorites')
            ->add('isSectionFluid')
            ->add('position')
            ->add('isShowtitle')
            ->add('isShowdescription')
            ->add('cssBackgroundImageFile', VichImageType::class, [
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
            'data_class' => Section::class,
        ]);
    }
}
