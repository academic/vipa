<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\AdminBundle\Entity\AdminPage;
use Ojs\AdminBundle\Entity\AdminPost;
use Ojs\CoreBundle\Controller\OjsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CmsController extends OjsController
{
    /**
     * @param AdminPage $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("page", options={"mapping": {"slug": "slug"}})
     */
    public function pageAction(AdminPage $page)
    {
        return $this->render(
            'OjsSiteBundle:Cms:page.html.twig',
            ['page' => $page]
        );
    }
    /**
     * @param AdminPost $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     */
    public function postAction(AdminPost $page)
    {
        return $this->render(
            'OjsSiteBundle:Cms:post.html.twig',
            ['post' => $page]
        );
    }
}
