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
class ImageSelectorType extends AbstractType
{
    private $manager;
    private $class;

    public function __construct(\Doctrine\ODM\PHPCR\DocumentManager $manager, $class)
    {
        $this->manager = $manager;
        $this->class = $class;
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $manager = $this->manager;
        $class = $this->class;
        $builder->addViewTransformer(new \Symfony\Component\Form\CallbackTransformer(
                        function($value) {
                            if ($value) {
                                return $value->getId();
                            }
                        },
                        function($value) use ($manager, $class) {
                            if (!$value) {
                                return $value;
                            }

                            return $manager->find($class, $value);
                        }
        ));
    }

    public function getName()
    {
        return 'burgov_image_selector';
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => false,
            'cascade_validation' => false
        ));
    }

    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options)
    {
        $name = '';
        if (null !== $form->getData()) {
            $image = $form->getData();

            $path = explode('/', $image->getId());
            $name = end($path);
        }

        $view->set('image_name', $name);
    }

}
