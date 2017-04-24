<?php

namespace WebShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\Product;
use WebShopBundle\Form\SellProductForm;

/**
 * Class UserProductsController
 * @package WebShopBundle\Controller
 *
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
 */
class UserProductsController extends Controller
{
    /**
     * @Route("/sell/{slug}", name="sell_user_product")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function sellProductAction(Request $request, Product $product)
    {
        $form = $this->createForm(SellProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newProduct = new Product();
            $newProduct->setName($product->getName());
            $newProduct->setCategory($product->getCategory());
            $newProduct->setDescription($product->getDescription());
            $newProduct->setImageName($product->getImageName());
            $newProduct->setQuantity(1);
            $newProduct->setPrice($product->getPrice());
            $newProduct->setSeller($this->getUser());
            $newProduct->setPromotions($product->getPromotions());
            $newProduct->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($newProduct);
            $em->flush();

            $this->addFlash("success", "Product added for sale successfully!");

            return $this->redirectToRoute("user_orders");
        }

        return $this->render("@WebShop/products/sell.html.twig", [
            "add_form" => $form->createView()
        ]);
    }
}
