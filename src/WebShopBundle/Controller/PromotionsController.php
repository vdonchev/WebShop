<?php

namespace WebShopBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\Promotion;

class PromotionsController extends Controller
{
    /**
     * @Route("/promotions", name="promotions_list")
     *
     * @return Response
     */
    public function listPromotionsAction()
    {
        return $this->render("@WebShop/promotions/list.html.twig", [
            "promotions" => $this->getDoctrine()->getRepository(Promotion::class)
                ->findNotExpired()
        ]);
    }

    /**
     * @Route("/promotions/view/{id}", name="promotions_view")
     *
     * @param Promotion $promotion
     * @param Request $request
     * @return Response
     */
    public function viewPromotionAction(Promotion $promotion, Request $request)
    {
        $pager = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $promotion->getProducts(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@WebShop/promotions/view.html.twig", [
            "promotion" => $promotion,
            "products" => $products
        ]);
    }
}
