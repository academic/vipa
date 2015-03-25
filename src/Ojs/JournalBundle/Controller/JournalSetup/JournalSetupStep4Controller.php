<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Okulbilisim\CmsBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step4;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalSetupStep4Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 4 - Saves Journal 's step 4 data
     * @param Request $request
     * @param null $journalId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }
        $data = $request->request->all();
        $pages = $data['page'];
        $twig = $this->get('okulbilisimcmsbundle.twig.post_extension');
        foreach ($pages as $page) {
            if (empty($page['title'])) {
                return new JsonResponse([
                    'success' => "0"
                ]);
            }
            $page_ = new Post();
            $page_
                ->setStatus(1)
                ->setContent($page['content'])
                ->setObject($twig->encode($journal))
                ->setObjectId($journal->getId())
                ->setPostType('default')
                ->setTitle($page['title']);
            $em->persist($page_);
            $em->flush();
        }

        return new JsonResponse(array(
            'success' => '1'));
    }
}