<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AddproductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productname')
            ->add('type')
            ->add('qte')
            ->add('price')
            ->add('descriptions')
            ->add('picture')
            ->add('likes')
            ->add('size')
            ->add('color')
            ->add('boutiqueid')
            ->add('Categorie')
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
