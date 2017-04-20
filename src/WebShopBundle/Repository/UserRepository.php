<?php

namespace WebShopBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package WebShopBundle\Repository
 */
class UserRepository extends EntityRepository
{
    public function findByQueryBuilder()
    {
        return $this->createQueryBuilder("user");
    }
}