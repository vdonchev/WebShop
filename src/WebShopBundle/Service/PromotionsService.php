<?php

namespace WebShopBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\ProductCategory;
use WebShopBundle\Entity\Promotion;

class PromotionsService implements PromotionsServiceInterface
{
    private $entityManager;
    private $session;
    private $manager;

    public function __construct(
        EntityManagerInterface $entityManager,
        Session $session,
        ManagerRegistry $manager)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->manager = $manager;
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

    public function setPromotionToProducts(Promotion $promotion)
    {
        $allProducts = $this->manager->getRepository(Product::class)
            ->findAll();

        foreach ($allProducts as $product) {
            if ($product->getPromotions()->contains($promotion)) {
                continue;
            }

            $product->setPromotion($promotion);
        }

        $this->entityManager->persist($promotion);
        $this->entityManager->flush();

        $this->session
            ->getFlashBag()
            ->add("success", "All products was promoted with '{$promotion->getName()}' promotion");
    }

    public function unsetPromotionToProducts(Promotion $promotion)
    {
        $allProducts = $this->manager->getRepository(Product::class)
            ->findAll();

        foreach ($allProducts as $product) {
            if (!$product->getPromotions()->contains($promotion)) {
                continue;
            }

            $product->unsetPromotion($promotion);
        }

        $this->entityManager->persist($promotion);
        $this->entityManager->flush();

        $this->session
            ->getFlashBag()
            ->add("success", "'{$promotion->getName()}' promotion was removed from all products!");
    }
}