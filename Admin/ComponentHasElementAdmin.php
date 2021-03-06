<?php
namespace Purethink\AdminBundle\Admin;

use Purethink\CMSBundle\Entity\ComponentHasValue;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Purethink\CMSBundle\Entity\ComponentHasElement;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Form\Type\Filter\DateType;

class ComponentHasElementAdmin extends AbstractAdmin
{
    protected $formOptions = [
        'cascade_validation' => true
    ];

    protected $parentAssociationMapping = 'component';

    public $last_position = 0;

    private $container;
    /** @var PositionHandler */
    private $positionService;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'position',
        'createdAt'   => ['type' => DateType::TYPE_GREATER_THAN],
        'updatedAt'   => ['type' => DateType::TYPE_GREATER_THAN]
    ];

    public function setPositionService(PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('move', $this->getRouterIdParameter() . '/move/{position}')
            ->remove('export');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.general', ['class' => 'col-md-8'])
            ->add('componentHasValues', 'sonata_type_collection', [
                'label'        => false,
                'btn_add'      => false,
                'type_options' => [
                    'delete'  => false,
                    'btn_add' => false,
                    'label'   => false
                ]
            ], [
                'sortable' => 'position'
            ])
            ->end()
            ->with('admin.options', ['class' => 'col-md-4'])
            ->add('enabled', null, [
                'label' => 'admin.component_has_element.enabled'
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('enabled', null, [
                'label' => 'admin.component_has_element.enabled'
            ])
            ->add('createdAt', 'doctrine_orm_datetime', [
                'label'         => 'admin.created_at',
                'field_type'    => 'sonata_type_datetime_picker',
                'field_options' => [
                    'format' => 'dd MMM yyyy, HH:mm',
                ]
            ])
            ->add('updatedAt', 'doctrine_orm_datetime', [
                'label'         => 'admin.updated_at',
                'field_type'    => 'sonata_type_datetime_picker',
                'field_options' => [
                    'format' => 'dd MMM yyyy, HH:mm',
                ]
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        if ($this->getParentObject()) {
            $this->last_position = $this->getParentObject()->getElements()->count() - 1;
        }

        $listMapper
            ->addIdentifier('title', null, [
                'label' => 'admin.component_has_element.title'
            ])
            ->add('position', null, [
                'label'    => 'admin.component_has_element.position',
                'editable' => true
            ])
            ->add('createdAt', null, [
                'label' => 'admin.created_at'
            ])
            ->add('updatedAt', null, [
                'label' => 'admin.updated_at'
            ])
            ->add('enabled', null, [
                'label'    => 'admin.component_has_element.enabled',
                'editable' => true
            ])
            ->add('_action', 'actions', [
                'label'   => 'admin.actions',
                'actions' => [
                    'move' => ['template' => 'PurethinkAdminBundle:Admin:_sort.html.twig'],
                ]
            ]);
    }

    public function getNewInstance()
    {
        /** @var ComponentHasElement $element */
        $element = parent::getNewInstance();
        $element->setComponent($this->getParentObject());

        $fields = $element->getComponent()->getExtension()->getFields();
        foreach ($fields as $field) {
            $componentHasValue = ComponentHasValue::getComponentHasValueType($element, $field);
            $element->addComponentHasValue($componentHasValue);
        }

        return $element;
    }

    protected function getParentObject()
    {
        if ($this->getParent()) {
            return $this->getParent()->getObject($this->getParent()->getRequest()->get('id'));
        }

        return null;
    }
}
