<?php

namespace Purethink\AdminBundle\Admin;

use Sonata\MediaBundle\Admin\ORM\MediaAdmin as BaseMediaAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;

class MediaAdmin extends BaseMediaAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('export');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper->remove('category');
    }
}