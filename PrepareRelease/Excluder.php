<?php
/*
 * This file is part of the Burgov CMS.
 *
 * (c) Bart van den Burg <bart@burgov.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Burgov\Bundle\CMSBundle\PrepareRelease;

use Samson\Bundle\ReleaseBundle\PrepareRelease\ExcluderInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */
class Excluder implements ExcluderInterface
{
    public function process(Finder $finder)
    {
        $finder
            ->exclude(array(
                'bin',
                'design',
                'web/media/cache'
            ))
            ->notName('parameters.yml')
        ;
    }

}
