<?php

namespace Purethink\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LanguageAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_by' => 'name'
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.general', ['class' => 'col-md-8'])
            ->add('name', null, [
                'label' => 'admin.language.name'
            ])
            ->add('alias', null, [
                'label' => 'admin.language.alias'
            ])
            ->end()
            ->with('admin.options', ['class' => 'col-md-4'])
            ->add('enabled', null, [
                'label'    => 'admin.language.enabled',
                'required' => false
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, [
                'label' => 'admin.language.name'
            ])
            ->add('alias', null, [
                'label' => 'admin.language.alias'
            ])
            ->add('enabled', null, [
                'label' => 'admin.language.enabled'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, [
                'label' => 'admin.language.name'
            ])
            ->add("alias", null, [
                'label' => 'admin.language.alias'
            ])
            ->add('enabled', null, [
                'label'    => 'admin.language.enabled',
                'editable' => true
            ]);
    }

}
