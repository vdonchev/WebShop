<?php

namespace WebShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion")
 * @ORM\Entity(repositoryClass="WebShopBundle\Repository\PromotionRepository")
 * @UniqueEntity(fields={"name"})
 */
class Promotion
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="Promotion name should be at least 3 characters long.")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min="1", max="99")
     * @Assert\Regex("/\d{1,2}/")
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="datetime")
     * @Assert\GreaterThan("today")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity="WebShopBundle\Entity\Product", mappedBy="promotions")
     *
     * @var ArrayCollection
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    public function getProductsWithActivePromo()
    {
        return $this->products->filter(function (Product $p) {
            return $p->getActualPromotion()->getName() == $this->getName();
        });
    }

    public function __toString()
    {
        return $this->name;
    }
}

