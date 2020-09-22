<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('image', FileType::class, [
                'mapped' => false, // On ne lie pas le champ à la BDD
                // @todo: validation d'une image
                // 10 Mo max et uniquement jpg, png et gif,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/gif',
                            'image/png',
                            'image/jpeg'
                        ],
                        'maxSize' => '10m',
                    ]),
                ]
            ])
            ->add('description')
            ->add('category', null, [
                'choice_label' => 'name',
                // 'expanded' => true,
                // 'multiple' => true,
            ])
            ->add('tags', null, [
                'expanded' => true, // Permet d'afficher des checkboxes
                // 'multiple' => true,
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100, // Dans le formulaire, on saisit 99.99 mais on prend comme valeur 9999
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
