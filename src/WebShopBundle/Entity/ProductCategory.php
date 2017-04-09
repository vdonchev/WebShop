<?php

namespace WebShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductCategory
 *
 * @ORM\Table(name="product_category")
 * @ORM\Entity(repositoryClass="WebShopBundle\Repository\ProductCategoryRepository")
 */
class ProductCategory
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
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_on", type="datetime", nullable=true)
     */
    private $deletedOn;

    /**
     * @ORM\OneToMany(targetEntity="WebShopBundle\Entity\Product", mappedBy="category")
     */
    private $products;

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

    public function setDeletedOn($deletedOn)
    {
        $this->deletedOn = $deletedOn;

        return $this;
    }

    public function getDeletedOn()
    {
        return $this->deletedOn;
    }

    public function getProducts()
    {
        return $this->products;
    }
}

