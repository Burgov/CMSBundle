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

use Burgov\Bundle\CMSBundle\Entity\User;
use Burgov\Bundle\CMSBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Bart van den Burg <bart@burgov.nl>
 */
class UserController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getEntityManager()->getRepository('BurgovCMSBundle:User')->findAll();

        return array(
            'users' => $users
        );
    }

    /**
     * @Template()
     */
    public function editAction(User $user)
    {
        /* @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $this->get('fos_user.user_manager');
        $form = $this->createForm(new UserType(), $user);

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $userManager->updateUser($user);
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Gebruiker opgeslagen');

                return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
            }
        }

        return array('form' => $form->createView(), 'user' => $user);
    }

    /**
     * @Template()
     */
    public function createAction()
    {
        /* @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $form = $this->createForm(new UserType(), $user);

        $form->add($this->get('form.factory')->createNamed('mail_password', 'checkbox', false, array(
            'required' => false,
            'mapped' => false,
            'label' => 'Stuur deze gebruiker gelijk zijn nieuwe wachtwoord'
        )));

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $user->setPlainPassword($password = $this->generatePassword(8));
                $userManager->updateUser($user);
                
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Gebruiker opgeslagen. Het wachtwoord is: "<em>'.$password.'</em>"');
                if ($form->get('mail_password')->getData()) {
                    $this->sendPassword($user, $password);
                }

                return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
            }
        }

        return array('form' => $form->createView(), 'user' => $user);
    }

    public function regeneratePasswordAction(User $user, $send_mail = false)
    {
        $password = $this->generatePassword(8);
        $user->setPlainPassword($password);
        /* @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $this->get('fos_user.user_manager');
        $userManager->updateUser($user);
        
        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Het nieuwe wahtwoord van deze gebruiker is: "<em>'.$password.'</em>"');

        if ($send_mail) {
            $this->sendPassword($user, $password);
        }

        return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
    }

    private function sendPassword(User $user, $password)
    {
        $message = \Swift_Message::newInstance('Uw wachtwoord voor de Louis Hartlooper Prijs website', <<<END
Beste {$user->getName()},

Hierbij ontvangt u uw wachtwoord om mee in te loggen op de website van de Louis Hartlooper Prijs:

{$password}

Met vriendelijke groet,
END
        , 'text/plain')
            ->setTo($user->getEmail(), $user->getName())
        ;

        $this->get('mailer')->send($message);
        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Het wachtwoord is naar '.$user->getEmail().' verstuurd');

    }

    private function generatePassword($length)
    {
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        foreach (array('1', 'l', 'I', 'O', '0') as $remove) {
            array_splice($chars, array_search($remove, $chars), 1);
        }

        $password = "";
        while ($length--) {
            $password .= $chars[rand(0, count($chars)-1)];
        }

        return $password;
    }
}
