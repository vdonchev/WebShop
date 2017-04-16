<?php

namespace WebShopBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\ProductCategory;
use WebShopBundle\Entity\Promotion;

class PromotionsService
{
    private $entityManager;
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        Session $session,
        OrderServiceInterface $orderService)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function setPromotionToCategory(Promotion $promotion, ProductCategory $category)
    {
        /** @var ArrayCollection|Product[] $products */
        $products = $category->getProducts();
        foreach ($products as $product) {
            if ($product->getPromotions()->contains($promotion)) {
                continue;
            }

            $product->setPromotion($promotion);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $this->session
            ->getFlashBag()
            ->add("success",
                "All products of category '{$category->getName()}' was promoted with '{$promotion->getName()}' promotion");
    }
}