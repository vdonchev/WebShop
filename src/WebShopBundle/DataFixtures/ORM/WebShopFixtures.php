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
            __DIR__.'/fixtures.yml',
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
            "static/products/01.png",
            "static/products/02.png",
            "static/products/03.png",
            "static/products/04.jpg",
            "static/products/05.png",
            "static/products/06.jpg",
        ];

        return $images[array_rand($images)];
    }
}
