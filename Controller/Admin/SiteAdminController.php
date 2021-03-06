<?php

namespace Purethink\AdminBundle\Controller\Admin;

use Purethink\CMSBundle\Entity\Site;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SiteAdminController extends CRUDController
{
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $sites = $this->getSiteRepository()->findAll();
        if (count($sites) > 0) {
            /** @var Site $site */
            $site = $sites[0];
        } else {
            $site = new Site();
            $this->getDoctrine()->getManager()->persist($site);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('admin_purethink_cms_site_edit', ['id' => $site->getId()]);
    }

    private function getSiteRepository()
    {
        return $this->getDoctrine()->getRepository('PurethinkCMSBundle:Site');
    }
}