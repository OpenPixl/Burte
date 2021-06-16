<?php

namespace App\Form\Webapp;

use App\Entity\Webapp\Article;
use App\Entity\Webapp\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('content')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('articleFrontFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Supprimer',
            'download_label' => 'Télecharger',
            ])
            ->add('state', ChoiceType::class, [
                'choices'  => [
                    'Brouillon' => 'draft',
                    'Publiée' => 'publish',
                ],
            ])
            ->add('articleFrontPosition', ChoiceType::class, [
                'choices'  => [
                    'à droite du contenu' => 'right',
                    'à gauche du contenu' => 'left',
                ],
            ])
            ->add('isReadMore')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
