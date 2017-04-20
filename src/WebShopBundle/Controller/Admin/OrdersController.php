<?php

namespace WebShopBundle\Controller\Admin;

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
 * @Route("/admin/orders")
 * @Security("is_granted('ROLE_EDITOR')")
 */
class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("", name="admin_list_orders")
     */
    public function listOrdersAction(Request $request)
    {
        $pager = $this->get('knp_paginator');
        $orders = $pager->paginate(
            $this->getDoctrine()->getRepository(ProductsOrder::class)
                ->findByQueryBuilder()->orderBy("products_order.date", "desc"),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render("@WebShop/admin/orders/list.html.twig", [
            "orders" => $orders
        ]);
    }

    /**
     * @param ProductsOrder $order
     * @return Response
     *
     * @Route("/complete/{id}", name="admin_verify_order")
     */
    public function completeOrder(ProductsOrder $order)
    {
        if ($order->getVerified() == true) {
            $this->addFlash("danger", "This order is already verified.");

            return $this->redirectToRoute("admin_list_orders");
        }

        $order->setVerified(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash("success", "Order was verified successfully.");

        return $this->redirectToRoute("admin_list_orders");
    }
}
