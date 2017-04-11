<?php

namespace WebShopBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use WebShopBundle\Entity\Product;
use WebShopBundle\Entity\User;

class CartService
{
    private $entityManager;
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        Session $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * @param Product[]|ArrayCollection $products
     * @return float
     */
    public function getProductsTotal($products)
    {
        if ($products->count() == 0) {
            return 0.;
        }

        $total = array_sum(
            array_map(function (Product $p) {
                return $p->getPrice();
            }, $products->toArray())
        );

        return $total;
    }

    /**
     * @param User $user
     * @param Product $product
     */
    public function removeProductFromCart(User $user, Product $product)
    {
        if (!$user->getProducts()->removeElement($product)) {
            $this->session->getFlashBag()->add("danger", "Cart was not updated!");
            return;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add("success", "Cart updated!");
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addProductToCart(User $user, Product $product)
    {
        if ($user->getProducts()->contains($product)) {
            $this->session->getFlashBag()->add("danger", "Product already exists in your cart.");
            return false;
        }

        $user->getProducts()->add($product);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add("success", "Product added to cart updated.");

        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkoutCart(User $user)
    {
        $userFunds = $user->getFunds();
        $cartTotal = $this->getProductsTotal($user->getProducts());
        $cartProducts = $user->getProducts();

        foreach ($cartProducts as $product) {
            if ($product->getQuantity() <= 0) {
                $this->session->getFlashBag()
                    ->add("danger", "Some of the products in your cart are out of stock.");

                return false;
            }
        }

        if ($cartTotal > $userFunds) {
            $this->session->getFlashBag()
                ->add("danger", "You do not have enough funds in your account to complete the order");

            return false;
        }

        foreach ($cartProducts as $product) {
            $product->setQuantity($product->getQuantity() - 1);
            $user->getProducts()->removeElement($product);
        }

        $user->setFunds($userFunds - $cartTotal);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add("success", "Checkout completed! Enjoy your order!");

        return true;
    }
}