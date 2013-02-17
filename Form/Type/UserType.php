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
class UserType extends AbstractType
{
    public function getName()
    {
        return 'user';
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, array('label' => 'Gebruikersnaam'));
        $builder->add('email', null, array('label' => 'E-mailadres'));
        $builder->add('groups', null, array('expanded'=>true, 'label'=>'Gebruikersgroepen'));

        $builder->add('firstName', null, array('label' => 'Voornaam'));
        $builder->add('preposition', null, array('label' => 'Tussenvoegsel', 'attr' => array('size' => 10)));
        $builder->add('lastName', null, array('label' => 'Achternaam'));
    }

}
