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

use FOS\UserBundle\Entity\Group as BaseUserGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_usergroup")
 */
class UserGroup extends BaseUserGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Burgov\Bundle\CMSBundle\Entity\User", mappedBy="groups")
     */
    protected $users;

    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
