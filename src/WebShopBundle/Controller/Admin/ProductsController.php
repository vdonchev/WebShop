<?php

namespace WebShopBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\Promotion;
use WebShopBundle\Form\AddProductForm;
use WebShopBundle\Form\EditProductForm;

/**
 * Class ProductsController
 * @package WebShopBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_EDITOR')")
 */
class ProductsController extends Controller
{
    /**
     * @Route("/products", name="admin_list_products")
     *
     * @return Response
     */
    public function listProductsAction(Request $request)
    {
        $pager  = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findByQueryBuilder()->orderBy("product.id", "desc"),
            $request->query->getInt('page', 1),
            5,
            ['defaultSortDirection' => 'asc']
        );

        return $this->render("@WebShop/admin/products/list.html.twig", [
            "products" =>$products
        ]);
    }

    /**
     * @Route("/products/add", name="admin_add_product")
     *
     * @param Request $request
     * @return Response
     */
    public function addProductAction(Request $request)
    {
        $form = $this->createForm(AddProductForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} added successfully.");

            return $this->redirectToRoute("admin_list_products");
        }

        return $this->render("@WebShop/admin/products/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{slug}", name="admin_edit_product")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function editProductAction(Request $request, Product $product)
    {
        $form = $this->createForm(EditProductForm::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} added successfully.");

            return $this->redirectToRoute("admin_list_products");
        }

        return $this->render("@WebShop/admin/products/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/delete/{slug}", name="admin_delete_product")
     *
     * @param Product $product
     * @return Response
     */
    public function deleteProductAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash("success", "Product {$product->getName()} was deleted successfully.");

        return $this->redirectToRoute("admin_list_products");
    }
}
