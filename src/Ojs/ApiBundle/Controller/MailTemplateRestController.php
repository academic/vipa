<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MailTemplateRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Mail Templates",
     * )
     * @Get("/public/mail_templates")
     */
    public function getMailTemplatesAction()
    {
        $templates = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:MailTemplate')
            ->findBy(['journalId' => null]);

        return $templates;
    }

    /**
     * @ApiDoc(
     *    resource=true,
     *    description="Get journal mail templates",
     *    filters={
     *      {"name"="journal", "dataType"="string"}
     *    }
     * )
     * @Get("/public/mail_templates/{journal}")
     * @param $journal
     * @return array
     */
    public function getJournalMailTemplatesAction($journal)
    {
        $templates = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:MailTemplate')
            ->findBy(['journalId' => $journal]);

        return $templates;
    }

    /**
     * @ApiDoc(
     *    resource=true,
     *    description="Get mail template by id",
     *    filters={
     *       {"name"="id", "dataType"="string"}
     *    }
     * )
     * @Get("/public/mail_template/{id}")
     *
     * @param $id
     * @return object
     */
    public function getMailTemplateAction($id)
    {
        $template = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:MailTemplate')
            ->find($id);

        return $template;
    }
}
