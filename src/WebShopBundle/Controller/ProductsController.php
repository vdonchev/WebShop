<?php

namespace WebShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Product;

/**
 * Class ProductsController
 * @package WebShopBundle\Controller
 *
 * @Route("/products")
 */
class ProductsController extends Controller
{
    /**
     * @Route("/view/{id}", name="products_view_product")
     *
     * @param Product $product
     * @return Response
     */
    public function viewProductAction(Product $product)
    {
        return $this->render("@WebShop/products/view_product.html.twig", [
            "product" => $product
        ]);
    }
}
