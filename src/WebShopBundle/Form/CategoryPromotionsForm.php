<?php

namespace WebShopBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use WebShopBundle\Entity\ProductCategory;
use WebShopBundle\Entity\Promotion;

class CategoryPromotionsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("promotion", EntityType::class, [
                "class" => Promotion::class,
                "placeholder" => "Choose promotion",
                "constraints" => [
                    new NotBlank()
                ]
            ])
            ->add("category", EntityType::class, [
                "class" => ProductCategory::class,
                "label" => "Products category",
                "placeholder" => "Choose category",
                "constraints" => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
