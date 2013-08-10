<?php
/*
 * This file is part of the Burgov CMS.
 *
 * (c) Bart van den Burg <bart@burgov.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Burgov\Bundle\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */
class UserGroupType extends AbstractType
{
    public function getName()
    {
        return 'user_group';
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'Naam'));
        $builder->add('uniqueCode');
    }

}
