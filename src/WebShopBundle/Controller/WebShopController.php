<?php

namespace WebShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebShopBundle\Entity\ProductCategory;

class WebShopController extends Controller
{
    public function sidebarMenuAction()
    {
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)
            ->getNonEmptyCategories();

        return $this->render("@WebShop/_categories_menu.html.twig", [
            "categories" => $categories
        ]);
    }
}
