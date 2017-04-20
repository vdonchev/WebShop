<?php

namespace WebShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 * @UniqueEntity(fields={"name"})
 */
class Role implements RoleInterface
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
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="WebShopBundle\Entity\User", mappedBy="roles")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getRole()
    {
        return $this->name;
    }

    public function __toString() {
        return ucfirst(strtolower(explode("_", $this->name)[1]));
    }
}

