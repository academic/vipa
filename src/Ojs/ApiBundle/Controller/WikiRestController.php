<?php
/**
 * User: aybarscengaver
 * Date: 26.11.14
 * Time: 11:17
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\ApiBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\WikiBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Request;

class WikiRestController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Create new page",
     *  requirements={
     *  }
     * )
     * @QueryParam(name="id",nullable=true)
     * @Put("/wiki/page/create/{object}/{type}")
     */
    public function putPageAction(Request $request, $type, $object)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $page = $id ? $em->find('OjsWikiBundle:Page', $id) : new Page();
        $page->setTitle($request->get('title'));
        $page->setContent($request->get('content'));
        switch ($type) {
            case "journal";
                $journal = $em->find('OjsJournalBundle:Journal', $object);
                $page->setJournalId($object);
                $page->setJournal($journal);
                break;
        }
        $page->setTags($request->get('tags'));

        $em->persist($page);

        $em->flush();
        return [
            'status' => true,
            'page' => $page
        ];
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a page"
     * )
     * @Delete("/wiki/page/delete/{id}")
     */
    public function deletePageAction(Request $request, $id)
    {

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->find('OjsWikiBundle:Page', $id);
        $em->remove($page);
        $em->flush();

        return [
            'status' => true
        ];
    }
}