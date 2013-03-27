<?php
/*
 * This file is part of the Burgov CMS.
 *
 * (c) Bart van den Burg <bart@burgov.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Burgov\Bundle\CMSBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true, length=10)
     * @Assert\MaxLength(10)
     */
    private $preposition;

    /**
     * @ORM\ManyToMany(targetEntity="Burgov\Bundle\CMSBundle\Entity\UserGroup", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group")
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
        $this->setEnabled(true);
        // your own logic
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getPreposition()
    {
        return $this->preposition;
    }

    public function setPreposition($preposition)
    {
        $this->preposition = $preposition;
    }

    public function getName()
    {
        $parts = array();
        $parts[] = $this->getFirstName();
        if (strlen($this->getPreposition())) $parts[] = $this->getPreposition();
        $parts[] = $this->getLastName();

        return implode(" ", $parts);
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
