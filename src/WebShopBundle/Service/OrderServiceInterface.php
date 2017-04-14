<?php

namespace WebShopBundle\Service;

use WebShopBundle\Entity\User;

interface OrderServiceInterface
{
    public function createOrder(
        User $user,
        \DateTime $date,
        array $products,
        $total);
}