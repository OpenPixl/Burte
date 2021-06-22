<?php

namespace App\Form\Webapp;

use App\Entity\Webapp\Article;
use App\Entity\Webapp\Section;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                    'Un article' => 'One_article',
                    'Plusieurs articles' => 'More_article',
                    'Une categorie' => 'Category',
                    'Un évènement' => 'One_event',
                    'les évènements' => 'Events',
                    "bulletin d'adhésion" => "Adhesion",
                    'Autres' => 'Others'
                ],
            ])
            ->add('oneArticle')
            ->add('favorites')
            ->add('isSectionFluid')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
