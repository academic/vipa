<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Ojs\JournalBundle\Entity\Author;

class ArticleSubmissionStep2Controller extends Controller
{

    public function addAuthorsAction(Request $request)
    {
        $authorsData = json_decode($request->request->get('authorsData'));
        if (empty($authorsData)) {
            return new \Symfony\Component\HttpFoundation\Response('', 400);
        }
        $em = $this->getDoctrine()->getManager();
        $authorIds = array();
        $authors = array();
        foreach ($authorsData as $authorData) {
            $authorId = $authorData->authorid;
            if (empty($authorId)) {
                $author = new Author();
                $author->setFirstName($authorData->firstName);
                $author->setMiddleName($authorData->middleName);
                $author->setLastName($authorData->lastName);
                $author->setEmail($authorData->email);
                $author->setSummary($authorData->summary);
                $author->setInitials($authorData->initials);
                $em->persist($author);
                $authors[$authorData->order] = $author;
            } else {
                $authors[$authorData->order] = $this->getDoctrine()->getRepository('OjsJournalBundle:Author')->find($authorId);
            }
        }
        $em->flush();
        foreach ($authors as $k => $v) {
            $authorIds[$k] = $v->getId();
        }
        return new JsonResponse($authorIds);
    }

}
