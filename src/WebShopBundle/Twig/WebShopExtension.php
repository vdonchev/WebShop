<?php

namespace WebShopBundle\Twig;

class WebShopExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('roles', [$this, 'rolesFilter']),
        ];
    }

    public function rolesFilter($roles)
    {
        return join(", ", array_map(function ($role) {
            return ucfirst(strtolower(explode("_", $role)[1]));
        }, $roles));
    }
}