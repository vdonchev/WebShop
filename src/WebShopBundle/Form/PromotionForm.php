<?php

namespace WebShopBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use WebShopBundle\Entity\Promotion;

class PromotionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("promotion", EntityType::class, [
                "class" => Promotion::class,
                "placeholder" => "Select promotion",
                "constraints" => [
                    new NotBlank()
                ]
            ]);
    }
}
