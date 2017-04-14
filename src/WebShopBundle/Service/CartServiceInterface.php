<?php

namespace WebShopBundle\Service;

use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\User;

interface CartServiceInterface
{
    public function getProductsTotal($products);

    public function removeProductFromCart(User $user, Product $product);

    public function addProductToCart(User $user, Product $product);

    public function checkoutCart(User $user);
}