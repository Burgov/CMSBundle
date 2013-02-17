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

use Burgov\Bundle\CMSBundle\Entity\UserGroup;
use Burgov\Bundle\CMSBundle\Form\Type\UserGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */

class UserGroupController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $groups = $this->getDoctrine()->getEntityManager()->getRepository('BurgovCMSBundle:UserGroup')->findAll();

        return array(
            'groups' => $groups
        );
    }

    /**
     * @Template()
     */
    public function editAction(UserGroup $userGroup)
    {
        $form = $this->createForm(new UserGroupType(), $userGroup);

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Gebruikersgroep opgeslagen');

                return $this->redirect($this->generateUrl('usergroup_edit', array('id' => $userGroup->getId())));
            }
        }

        return array('form' => $form->createView(), 'userGroup' => $userGroup);
    }

    /**
     * @Template()
     */
    public function createAction()
    {
        $userGroup = new UserGroup('');
        $form = $this->createForm(new UserGroupType(), $userGroup);

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($userGroup);
                $em->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Gebruikersgroep opgeslagen');

                return $this->redirect($this->generateUrl('usergroup_edit', array('id' => $userGroup->getId())));
            }
        }

        return array('form' => $form->createView(), 'userGroup' => $userGroup);
    }
}
