<?php

namespace WebShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="WebShopBundle\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     * @Assert\NotNull(groups={"NewProduct"}, message="You should provide a product image.")
     * @Assert\Image()
     * @var File
     */
    private $imageFile;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min="0", max="999")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=14, scale=2)
     * @Assert\NotBlank()
     * @Assert\Range(min="0.01")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="WebShopBundle\Entity\ProductCategory", inversedBy="products", fetch="EXTRA_LAZY")
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="WebShopBundle\Entity\User", mappedBy="products")
     *
     * @var ArrayCollection
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="WebShopBundle\Entity\Promotion", inversedBy="products")
     * @ORM\JoinTable(name="product_promotions")
     *
     * @var ArrayCollection
     */
    private $promotions;

    /**
     * @ORM\OneToMany(targetEntity="WebShopBundle\Entity\Review", mappedBy="product")
     * @ORM\OrderBy({"date" = "DESC"})
     *
     * @var Review[]|ArrayCollection $reviews
     */
    private $reviews;

    /**
     * @ORM\Column(nullable=false, type="string", unique=true)
     * @Gedmo\Slug(fields={"name"})
     * @var string $slug
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="WebShopBundle\Entity\User", inversedBy="ownedProducts")
     */
    private $seller;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        if ($this->hasActivePromotion()) {
            $discount = $this->price * $this->getActualPromotion()->getDiscount() / 100;
            return $this->price - $discount;
        }

        return $this->price;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Product
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getPromotions()
    {
        return $this->promotions;
    }

    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;

        return $this;
    }

    public function getReviews()
    {
        return $this->reviews;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSeller()
    {
        return $this->seller;
    }

    public function setSeller($seller)
    {
        $this->seller = $seller;

        return $this;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setPromotion(Promotion $promotion)
    {
        $this->promotions[] = $promotion;
    }

    public function unsetPromotion(Promotion $promotion)
    {
        $this->promotions->removeElement($promotion);
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Promotion|null
     */
    public function getActualPromotion()
    {
        $activePromotions = $this->promotions->filter(
            function (Promotion $p) {
                return $p->getEndDate() > new \DateTime("now") &&
                    $p->getStartDate() <= new \DateTime("now");
            });

        if ($activePromotions->count() == 0) {
            return null;
        }

        if ($activePromotions->count() == 1) {
            return $activePromotions->first();
        }

        $arr = $activePromotions->getValues();
        usort($arr, function (Promotion $p1, Promotion $p2) {
            return $p2->getDiscount() - $p1->getDiscount();
        });

        return $arr[0];
    }

    /**
     * @return float
     */
    public function getOriginalPrice()
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function hasActivePromotion()
    {
        return $this->getActualPromotion() !== null;
    }
}

