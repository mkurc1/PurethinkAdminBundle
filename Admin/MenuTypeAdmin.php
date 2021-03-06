<?php

namespace Purethink\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class MenuTypeAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_by' => 'name'
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('export');
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('admin.menu_type.side_menu.menu'),
            ['uri' => $admin->generateUrl('edit', compact('id'))]
        );

        $menu->addChild(
            $this->trans('admin.menu_type.side_menu.elements'),
            ['uri' => $admin->getRouteGenerator()->generate('admin_purethink_cms_menutype_menu_list', compact('id'))]
        );
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.general', ['class' => 'col-md-8'])
            ->add('name', null, [
                'label' => 'admin.menu_type.name'
            ])
            ->end()
            ->with('admin.options', ['class' => 'col-md-4'])
            ->add('slug', null, [
                'label'    => 'admin.menu_type.slug',
                "required" => false
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, [
                'label' => 'admin.menu_type.name'
            ])
            ->add('slug', null, [
                'label' => 'admin.menu_type.slug'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, [
                'label' => 'admin.menu_type.name',
                'route' => ['name' => 'app.admin.menu.list']
            ])
            ->add('slug', null, [
                'label' => 'admin.menu_type.slug'
            ])
            ->add('_action', 'actions', [
                'label'   => 'admin.actions',
                'actions' => [
                    'edit' => []
                ]
            ]);;
    }

}
