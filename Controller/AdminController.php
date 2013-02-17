<?php
/*
 * This file is part of the Burgov CMS.
 *
 * (c) Bart van den Burg <bart@burgov.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Burgov\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */
class AdminController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

}
