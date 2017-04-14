<?php

namespace WebShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsOrder
 *
 * @ORM\Table(name="products_order")
 * @ORM\Entity(repositoryClass="WebShopBundle\Repository\ProductsOrderRepository")
 */
class ProductsOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="verified", type="boolean")
     */
    private $verified;

    /**
     * @var string[] $products
     *
     * @ORM\Column(type="json_array")
     */
    private $products;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="WebShopBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var float $total
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $total;

    public function getId()
    {
        return $this->id;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getVerified()
    {
        return $this->verified;
    }

    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts(array $products)
    {
        $this->products = $products;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }
}

