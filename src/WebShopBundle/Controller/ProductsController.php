<?php

namespace WebShopBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\ProductCategory;

/**
 * Class ProductsController
 * @package WebShopBundle\Controller
 *
 * @Route("/products")
 */
class ProductsController extends Controller
{
    /**
     * @Route("", name="products_list")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $pager = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)->findBy([], ["id" => "DESC"]),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@WebShop/products/list.html.twig", [
            "products" =>$products
        ]);
    }

    /**
     * @Route("/category/{id}", name="products_by_category")
     * @Method("GET")
     *
     * @param Request $request
     * @param ProductCategory $category
     * @return Response
     */
    public function listCategoryAction(Request $request, ProductCategory $category)
    {
        $pager = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)->findBy(["category" => $category], ["id" => "DESC"]),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@WebShop/products/list_by_category.html.twig", [
            "products" =>$products,
            "category" => $category
        ]);
    }

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
