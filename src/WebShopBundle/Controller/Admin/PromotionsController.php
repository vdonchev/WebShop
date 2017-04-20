<?php

namespace WebShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Promotion;
use WebShopBundle\Form\AddEditPromotionForm;
use WebShopBundle\Form\CategoryPromotionsForm;
use WebShopBundle\Form\PromotionForm;

/**
 * Class PromotionsController
 * @package WebShopBundle\Controller
 *
 * @Route("/admin/promotions")
 * @Security("is_granted('ROLE_EDITOR')")
 */
class PromotionsController extends Controller
{
    /**
     * @Route("", name="admin_list_promotions")
     *
     * @param Request $request
     * @return Response
     */
    public function listPromotionsAction(Request $request)
    {
        $pager = $this->get('knp_paginator');
        $promotions = $pager->paginate(
            $this->getDoctrine()->getRepository(Promotion::class)
                ->findByQueryBuilder()->orderBy("promotion.endDate", "desc"),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render("@WebShop/admin/promotions/list.html.twig", [
            "promotions" => $promotions
        ]);
    }

    /**
     * @Route("/promotions/delete/{id}", name="admin_delete_promotion")
     * @Method("POST")
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return Response
     */
    public function deletePromotionAction(Request $request, Promotion $promotion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();

        return $this->redirectToRoute("admin_list_promotions");
    }

    /**
     * @Route("/add", name="admin_add_promotion")
     *
     * @param Request $request
     * @return Response
     */
    public function addPromotionAction(Request $request)
    {
        $form = $this->createForm(AddEditPromotionForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Promotion $promotion */
            $promotion = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirectToRoute("admin_list_promotions");
        }

        return $this->render("@WebShop/admin/promotions/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_edit_promotion")
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return Response
     */
    public function editPromotionAction(Request $request, Promotion $promotion)
    {
        $form = $this->createForm(AddEditPromotionForm::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Promotion $promotion */
            $promotion = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirectToRoute("admin_list_promotions");
        }

        return $this->render("@WebShop/admin/promotions/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/category", name="admin_add_promotion_to_category")
     *
     * @param Request $request
     * @return Response
     */
    public function addPromotionToCategoryAction(Request $request)
    {
        $form = $this->createForm(CategoryPromotionsForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->get("promotion")->getData();
            $category = $form->get("category")->getData();

            $promoService = $this->get("web_shop.service.promotions_service");
            $promoService->setPromotionToCategory($promotion, $category);

            return $this->redirectToRoute("admin_list_promotions");
        }

        return $this->render("@WebShop/admin/promotions/add_category.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products", name="admin_add_promotion_to_all_products")
     *
     * @param Request $request
     * @return Response
     */
    public function addPromotionToAllProductsAction(Request $request)
    {
        $form = $this->createForm(PromotionForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->get("promotion")->getData();

            $promoService = $this->get("web_shop.service.promotions_service");
            $promoService->setPromotionToProducts($promotion);

            return $this->redirectToRoute("admin_list_promotions");
        }

        return $this->render("@WebShop/admin/promotions/add_all.html.twig", [
            "form_name" => "Add promotion to all Products",
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/remove", name="admin_remove_promotion_to_all_products")
     *
     * @param Request $request
     * @return Response
     */
    public function removePromotionFromAllProductsAction(Request $request)
    {
        $form = $this->createForm(PromotionForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->get("promotion")->getData();

            $promoService = $this->get("web_shop.service.promotions_service");
            $promoService->unsetPromotionToProducts($promotion);

            return $this->redirectToRoute("admin_list_promotions");
        }

        return $this->render("@WebShop/admin/promotions/add_all.html.twig", [
            "form_name" => "Remove promotion from all Products",
            "add_form" => $form->createView()
        ]);
    }
}
