<?php

namespace WebShopBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\ProductsOrder;
use WebShopBundle\Entity\User;

class OrderService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrder(
        User $user,
        \DateTime $date,
        array $products,
        $total)
    {

        $order = new ProductsOrder();
        $order->setUser($user);
        $order->setDate($date);
        $order->setProducts($products);
        $order->setTotal($total);
        $order->setVerified(false);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}