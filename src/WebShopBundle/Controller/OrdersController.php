<?php

namespace WebShopBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\ProductsOrder;
use WebShopBundle\Entity\User;

/**
 * Class OrdersController
 * @package WebShopBundle\Controller
 *
 * @Route("/orders")
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
 */
class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("", name="user_orders")
     */
    public function listOrdersAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $pager = $this->get('knp_paginator');
        /** @var ProductsOrder[]|ArrayCollection $orders */
        $orders = $pager->paginate(
            $this->getDoctrine()->getRepository(ProductsOrder::class)
                ->findByQueryBuilder()
                ->where("products_order.user = :user")
                ->setParameter("user", $currentUser)
                ->orderBy("products_order.date", "desc"),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render("@WebShop/orders/list.html.twig", [
            "orders" => $orders
        ]);
    }
}
