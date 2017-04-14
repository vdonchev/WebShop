<?php

namespace WebShopBundle\Twig;

class WebShopExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('roles', [$this, 'rolesFilter']),
            new \Twig_SimpleFilter('starRating', [$this, 'ratingFilter']),
            new \Twig_SimpleFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    public function rolesFilter($roles)
    {
        return join(", ", array_map(function ($role) {
            return ucfirst(strtolower(explode("_", $role)[1]));
        }, $roles));
    }

    public function ratingFilter($rating)
    {
        $rating = intval($rating);
        if ($rating < 1 || $rating > 5) {
            $rating = 1;
        }

        $res = str_repeat("<span class='glyphicon-star glyphicon'></span>", $rating);
        $rating = 5 - $rating;

        $res .= str_repeat("<span class='glyphicon-star-empty glyphicon'></span>", $rating);

        return $res;
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }
}