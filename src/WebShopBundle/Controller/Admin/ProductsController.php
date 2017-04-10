<?php

namespace WebShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Product;
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
    public function listProductsAction()
    {
        return $this->render("@WebShop/admin/products/list.html.twig", [
            "products" => $this->getDoctrine()->getRepository(Product::class)
                ->findBy([], ["id" => "DESC"])
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
     * @Route("/products/edit/{id}", name="admin_edit_product")
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
     * @Route("/products/delete/{id}", name="admin_delete_product")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function deleteProductAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash("success", "Product {$product->getName()} was deleted successfully.");

        return $this->redirectToRoute("admin_list_products");
    }
}
