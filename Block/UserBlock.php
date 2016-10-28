<?php

namespace Purethink\AdminBundle\Block;

use Doctrine\ORM\EntityManagerInterface;
use Purethink\CoreBundle\Block\AbstractBlock;
use Purethink\CoreBundle\Entity\Repository\UserRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class UserBlock extends AbstractBlock
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct($name, EngineInterface $templating, EntityManagerInterface $entityManager)
    {
        parent::__construct($name, $templating);
        $this->entityManager = $entityManager;
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'PurethinkAdminBundle:Block:user.html.twig'
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), [
            'qty' => $this->countUsers()
        ], $response);
    }

    private function countUsers() : int
    {
        return $this->getUserRepository()->countUsers();
    }

    private function getUserRepository() : UserRepository
    {
        return $this->entityManager->getRepository('PurethinkCoreBundle:User');
    }
}