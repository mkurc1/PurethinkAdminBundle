<?php

namespace Purethink\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleSourceAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('export')
            ->remove('list')
            ->remove('batch')
            ->remove('show');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, [
                'label' => 'admin.article_source.name'
            ])
            ->add('url', null, [
                'label' => 'admin.article_source.url'
            ]);
    }
}
