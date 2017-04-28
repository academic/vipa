<?php

namespace Vipa\SiteBundle\Controller;

use Vipa\AdminBundle\Entity\AdminPage;
use Vipa\AdminBundle\Entity\AdminPost;
use Vipa\CoreBundle\Controller\VipaController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CmsController extends VipaController
{
    /**
     * @param AdminPage $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("page", options={"mapping": {"slug": "slug"}})
     */
    public function pageAction(AdminPage $page)
    {
        return $this->render(
            'VipaSiteBundle:Cms:page.html.twig',
            [
                'page' => $page,
            ]
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
            'VipaSiteBundle:Cms:post.html.twig',
            [
                'post' => $page,
            ]
        );
    }
}
