<?php

namespace Purethink\AdminBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;

class LanguageAdmin extends AbstractAdmin
{
    public $last_position = 0;

    /**
     * @var PositionHandler
     */
    private $positionService;

    protected $datagridValues = [
        '_sort_by' => 'name'
    ];

    public function createQuery($context = 'list')
    {
        /** @var ProxyQuery $query */
        $query = parent::createQuery($context);
        /** @var QueryBuilder $qb */
        $qb = $query->getQueryBuilder();
        $qb->orderBy($qb->getRootAliases()[0] . '.position');

        return $query;
    }

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
            ->add('name', null, [
                'label' => 'admin.language.name'
            ])
            ->add('alias', null, [
                'label' => 'admin.language.alias'
            ])
            ->add('media', 'sonata_type_model_list', [
                'required' => false,
                'label'    => 'admin.language.media'
            ], [
                'link_parameters' => [
                    'context'  => 'default',
                    'provider' => 'sonata.media.provider.image'
                ]
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
        $this->last_position = $this->getLastPosition();

        $listMapper
            ->addIdentifier('name', null, [
                'label' => 'admin.language.name'
            ])
            ->add('position', null, [
                'label'    => 'admin.language.position',
                'editable' => true
            ])
            ->add("alias", null, [
                'label' => 'admin.language.alias'
            ])
            ->add('enabled', null, [
                'label'    => 'admin.language.enabled',
                'editable' => true
            ])
            ->add('_action', 'actions', [
                'label'   => 'admin.actions',
                'actions' => [
                    'move' => ['template' => 'PurethinkAdminBundle:Admin:_sort.html.twig'],
                ]
            ]);
    }

    private function getContainer()
    {
        return $this->getConfigurationPool()->getContainer();
    }
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    private function getLanguageRepository()
    {
        return $this->getEntityManager()->getRepository('PurethinkCMSBundle:Language');
    }

    private function getLastPosition()
    {
        return $this->getLanguageRepository()->getLastPosition();
    }
}
