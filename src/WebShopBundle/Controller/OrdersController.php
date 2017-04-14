<?php

namespace WebShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return Response
     *
     * @Route("", name="user_orders")
     */
    public function listOrdersAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        /** @var ProductsOrder[] $orders */
        $orders = $currentUser->getOrders();

        return $this->render("@WebShop/orders/list.html.twig", [
            "orders" => $orders
        ]);
    }
}
