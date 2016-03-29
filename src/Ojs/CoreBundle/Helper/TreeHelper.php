<?php

namespace Ojs\CoreBundle\Helper;


use Doctrine\Common\Collections\ArrayCollection;
use Ojs\JournalBundle\Entity\Subject;
use Symfony\Component\Routing\Router;

class TreeHelper
{
    const SUBJECT_ADMIN = 0;
    const SUBJECT_SEARCH = 1;

    /**
     * @param $type
     * @param Router $router
     * @param ArrayCollection|Subject[] $subjects
     * @param int|null $parentId
     * @return string
     */
    public static function createSubjectTreeView($type, $router, $subjects, $parentId = null)
    {
        $tree = '<ul>%s</ul>';
        $item = '<li>%s</li>';
        $link = '<a href="%s">%s</a>';
        $items = "";

        /**
         * @var Subject $subject
         * @var ArrayCollection $children
         */
        foreach ($subjects as $subject) {
            if($subject->getLvl() == 0){
                $link = '<a href="%s" class="top-subject-link">%s</a>';
            }
            if ($subject->getParent() === null || $subject->getParent()->getId() === $parentId) {

                if ($type == TreeHelper::SUBJECT_ADMIN) {
                    $path = $router->generate('ojs_admin_subject_show', ['id' => $subject->getId()]);
                } else {
                    $path = $router->generate('ojs_site_explore_index', ['subject_filters' => $subject->getSubject()]);
                }

                $content = sprintf($link, $path, $subject->getSubject());
                $children = $subject->getChildren();

                if ($children->count() > 0) {
                    $content = $content . TreeHelper::createSubjectTreeView($type, $router, $children, $subject->getId());
                }

                $items = $items.sprintf($item, $content);
            }
        }

        return sprintf($tree, $items);
    }

}