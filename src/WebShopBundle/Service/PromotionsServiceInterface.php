<?php

namespace WebShopBundle\Service;

use WebShopBundle\Entity\ProductCategory;
use WebShopBundle\Entity\Promotion;

interface PromotionsServiceInterface
{
    public function setPromotionToCategory(Promotion $promotion, ProductCategory $category);

    public function setPromotionToProducts(Promotion $promotion);

    public function unsetPromotionToProducts(Promotion $promotion);
}