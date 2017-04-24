<?php

namespace WebShopBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\ProductCategory;
use WebShopBundle\Entity\Review;
use WebShopBundle\Form\ReviewForm;

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
            $this->getDoctrine()->getRepository(Product::class)
                ->findByQueryBuilder()->orderBy("product.id", "desc"),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@WebShop/products/list.html.twig", [
            "products" => $products
        ]);
    }

    /**
     * @Route("/category/{slug}", name="products_by_category")
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
            $this->getDoctrine()->getRepository(Product::class)
                ->findAllByCategoryQueryBuilder($category)
                ->orderBy("product.id", "desc"),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@WebShop/products/list_by_category.html.twig", [
            "products" => $products,
            "category" => $category
        ]);
    }

    /**
     * @Route("/view/{slug}", name="products_view_product")
     *
     * @param Product $product
     * @return Response
     */
    public function viewProductAction(Product $product)
    {
        /** @var Review[] $reviews */
        $reviews = $product->getReviews();

        /** @var ProductCategory $category */
        $category = $product->getCategory();
        return $this->render("@WebShop/products/view_product.html.twig", [
            "product" => $product,
            "category_slug" => $category->getSlug(),
            "reviews" => $reviews
        ]);
    }

    /**
     * @Route("/{id}/review", name="products_add_review")
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function addReviewAction(Product $product, Request $request)
    {
        $form = $this->createForm(ReviewForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Review $review */
            $review = $form->getData();

            $review->setDate(new \DateTime());
            $review->setUser($this->getUser());
            $review->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash("success", "Review added!");

            return $this->redirectToRoute("products_view_product", ["slug" => $product->getSlug()]);
        }
        return $this->render("@WebShop/products/add_review.html.twig", [
            "add_form" => $form->createView()
        ]);
    }
}
