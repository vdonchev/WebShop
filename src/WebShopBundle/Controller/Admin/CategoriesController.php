<?php

namespace WebShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\ProductCategory;
use WebShopBundle\Form\AddEditCategoryForm;

/**
 * Class CategoriesController
 * @package WebShopBundle\Controller
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_EDITOR')")
 */
class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="admin_list_categories")
     * @param Request $request
     * @return Response
     */
    public function listCategoriesAction(Request $request)
    {
        $pager  = $this->get('knp_paginator');
        $categories = $pager->paginate(
            $this->getDoctrine()->getRepository(ProductCategory::class)
                ->findByQueryBuilder()->orderBy("product_category.name", "asc"),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render("@WebShop/admin/categories/list.html.twig", [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/categories/add", name="admin_add_category")
     * @param Request $request
     * @return Response
     */
    public function addCategoryAction(Request $request)
    {
        $form = $this->createForm(AddEditCategoryForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductCategory $category */
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was added!");

            return $this->redirectToRoute("admin_list_categories");
        }

        return $this->render("@WebShop/admin/categories/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/edit/{slug}", name="admin_edit_category")
     * @param Request $request
     * @param ProductCategory $category
     * @return Response
     */
    public function editCategoryAction(Request $request, ProductCategory $category)
    {
        $form = $this->createForm(AddEditCategoryForm::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductCategory $category */
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was updated!");

            return $this->redirectToRoute("admin_list_categories");
        }

        return $this->render("@WebShop/admin/categories/edit.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/delete/{id}", name="admin_delete_category")
     * @Method("POST")
     *
     * @param Request $request
     * @param ProductCategory $category
     * @return Response
     */
    public function deleteCategoryAction(Request $request, ProductCategory $category)
    {
        if ($category->getProducts()->count() > 0) {
            $this->addFlash("danger", "Category with products in it cannot be deleted.");

            return $this->redirectToRoute("admin_list_categories");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash("success", "Category {$category->getName()} was deleted!");

        return $this->redirectToRoute("admin_list_categories");
    }
}
