<?php

namespace WebShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Review
 *
 * @ORM\Table(name="review")
 * @ORM\Entity
 */
class Review
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
     * @ORM\Column(name="text", type="text")
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min="1", max="5")
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="WebShopBundle\Entity\Product", inversedBy="reviews")
     *
     * @var Product $product
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="WebShopBundle\Entity\User", inversedBy="reviews")
     *
     * @var User $user
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRating()
    {
        return $this->rating;
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

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;

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
}

