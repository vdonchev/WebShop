<?php

namespace WebShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebShopBundle\Entity\Product;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('WebShopBundle:default:index.html.twig', [
            "products" => $this->getDoctrine()->getRepository(Product::class)
                ->findLastProducts(9)
        ]);
    }
}
