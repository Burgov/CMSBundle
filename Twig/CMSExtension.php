<?php
/*
 * This file is part of the Burgov CMS.
 *
 * (c) Bart van den Burg <bart@burgov.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Burgov\Bundle\CMSBundle\Twig;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */
class CMSExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'cms';
    }

    public function getFilters()
    {
        return array(
            'cms_readmore' => new \Twig_Filter_Method($this, 'readmore'),
            'cms_article' => new \Twig_Filter_Method($this, 'article')
        );
    }

    public function readmore($html, $linkTarget, $linkIfNoReadMore = true)
    {
        $parts = preg_split('#<p.*?><span.*?>(\s|&nbsp;)*\.{3}(\s|&nbsp;)*</span></p>#', $html);

        return $parts[0].($linkIfNoReadMore || count($parts) > 1 ? '<p class="readmore"><a href="'.$linkTarget.'">Lees meer...</a></p>' : '');
    }

    public function article($html)
    {
        return preg_replace('#<p.*?><span.*?>(\s|&nbsp;)*\.{3}(\s|&nbsp;)*</span></p>#', '', $html);

    }
}
