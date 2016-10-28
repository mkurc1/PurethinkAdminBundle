<?php

namespace Purethink\AdminBundle\Block;

use Doctrine\ORM\EntityManagerInterface;
use Purethink\CMSBundle\Repository\ArticleRepository;
use Purethink\CMSBundle\Repository\ContactRepository;
use Purethink\CoreBundle\Block\AbstractBlock;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ContactBlock extends AbstractBlock
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
            'template' => 'PurethinkAdminBundle:Block:contact.html.twig'
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), [
            'qty' => $this->countContactRequest()
        ], $response);
    }

    private function countContactRequest() : int
    {
        return $this->getContactRepository()->countContactRequest();
    }

    private function getContactRepository() : ContactRepository
    {
        return $this->entityManager->getRepository('PurethinkCMSBundle:Contact');
    }
}