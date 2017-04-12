<?php

namespace WebShopBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WebShopBundle\Entity\Product;

class AddProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name")
            ->add("category", null, [
                "placeholder" => "Select category"
            ])
            ->add("description")
            ->add("imageFile", FileType::class, [
                'required' => true
            ])
            ->add("quantity")
            ->add("price", MoneyType::class)
            ->add("promotions", EntityType::class, [
                "class" => 'WebShopBundle\Entity\Promotion',
                "multiple" => true,
                "expanded" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Product::class,
            "validation_groups" => [
                "Default", "NewProduct"
            ]
        ]);
    }
}
