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
            "PRODUCT_01.jpg",
            "PRODUCT_02.jpg",
            "PRODUCT_03.jpg",
            "PRODUCT_04.jpg",
            "PRODUCT_05.png",
            "PRODUCT_06.png",
            "PRODUCT_07.png",
            "PRODUCT_08.png",
            "PRODUCT_09.jpg",
            "PRODUCT_10.jpg",
            "PRODUCT_11.jpg",
            "PRODUCT_12.jpg",
            "PRODUCT_13.png",
        ];

        $image = $images[array_rand($images)];

        $fileExt = explode(".", $image)[1];
        $oldPath = __DIR__ . "/../../../../web/_install/{$image}";

        $newImageName = uniqid() . "." . $fileExt;
        $newPath = __DIR__ . "/../../../../web/static/products/" . $newImageName;

        copy($oldPath, $newPath);
        return $newImageName;
    }
}
