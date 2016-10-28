<?php

namespace Purethink\AdminBundle\Block;

use Doctrine\ORM\EntityManagerInterface;
use Purethink\CMSBundle\Repository\ArticleRepository;
use Purethink\CoreBundle\Block\AbstractBlock;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ArticleBlock extends AbstractBlock
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
            'template' => 'PurethinkAdminBundle:Block:article.html.twig'
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), [
            'qty' => $this->countArticles()
        ], $response);
    }

    private function countArticles() : int
    {
        return $this->getArticleRepository()->countArticles();
    }

    private function getArticleRepository() : ArticleRepository
    {
        return $this->entityManager->getRepository('PurethinkCMSBundle:Article');
    }
}