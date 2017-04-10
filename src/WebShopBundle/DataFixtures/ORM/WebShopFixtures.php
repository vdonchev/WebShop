<?php

namespace WebShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class WebShopFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $objects = Fixtures::load(
            __DIR__ . '/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function product()
    {
        $products = [
            "iPhone 7 64GB",
            "Samsung Galaxy S8",
            "Acer Aspire 15KM",
            "Corsair K70 RGB",
            "Samsung QLED KLJ75",
            "Beats Headphones RED",
            "Kingston SSD 125GB",
            "Sony ILCE-6500Z",
            "Dell Alienware Area 51",
            "Gorenje SB800W",
            "Mirrorless Sony Alpha",
            "GIGABYTE GeForce® GTX 1070",
            "WD Purple 1TB",
            "Монитор Philips 18.5''"
        ];

        return $products[array_rand($products)];
    }

    public function productImage()
    {
        $images = [
            "IMG-04.jpg",
            "IMG-03.jpg",
            "IMG-02.jpg",
            "IMG-01.png",
            "IMG-00.png",
            "IMG-27.jpg",
            "IMG-26.jpg",
            "IMG-25.jpg",
            "IMG-24.jpg",
            "IMG-23.jpg",
            "IMG-22.jpg",
            "IMG-21.jpg",
            "IMG-20.jpg",
            "IMG-19.png",
            "IMG-18.png",
            "IMG-17.jpg",
            "IMG-16.jpg",
            "IMG-15.png",
            "IMG-14.png",
            "IMG-13.png",
            "IMG-12.png",
            "IMG-11.png",
            "IMG-10.png",
            "IMG-09.jpg",
            "IMG-08.jpg",
            "IMG-07.jpg",
            "IMG-06.jpg",
            "IMG-05.jpg"
        ];

        return $images[array_rand($images)];
    }
}
