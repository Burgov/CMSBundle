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

use FOS\RestBundle\View\View;
use Symfony\Cmf\Bundle\CreateBundle\Controller\PHPCRImageController;
use Symfony\Cmf\Bundle\CreateBundle\Model\ImageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */
class ImageController extends PHPCRImageController
{
    protected function generateUploadResponse($id, ImageInterface $image, UploadedFile $file)
    {
        return new RedirectResponse($this->router->generate('symfony_cmf_create_image_detail', array('name' => $this->getNameFromId($id))));
    }

    public function detailAction($name)
    {
        $id = $this->generateId($name);
        $image = $this->manager->find($this->imageClass, $id);

        if (!$image) {
            throw new NotFoundHttpException("Image '$name' not found at '$id'");
        }

        $url = $this->router->generate('symfony_cmf_create_image_display', array('name' => $this->getNameFromId($image->getId())), true);

        $data = array('id' => $image->getId(), 'url' => $url, 'alt' => $image->getCaption());

        $view = View::create($data);

        return $this->viewHandler->handle($view);
    }

    public function deleteAction($name)
    {
        $id = $this->generateId($name);
        $image = $this->manager->find($this->imageClass, $id);

        if (!$image) {
            throw new NotFoundHttpException("Image '$name' not found at '$id'");
        }

        $this->manager->remove($image);
        $this->manager->flush();

        $view = View::create(true);

        return $this->viewHandler->handle($view);
    }
}
