<?php

namespace Ojs\CoreBundle\Helper;


use Doctrine\Common\Collections\ArrayCollection;
use Ojs\JournalBundle\Entity\Subject;
use Symfony\Component\Routing\Router;

class TreeHelper
{
    /**
     * @param Router $router
     * @param ArrayCollection|Subject[] $subjects
     * @param int|null $parentId
     * @return string
     */
    public static function createSubjectTreeView($router, $subjects, $parentId = null)
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
            if ($subject->getParent() === null || $subject->getParent()->getId() === $parentId) {
                $path = $router->generate('ojs_admin_subject_show', ['id' => $subject->getId()]);
                $content = sprintf($link, $path, $subject->getSubject());
                $children = $subject->getChildren();

                if ($children->count() > 0) {
                    $content = $content . TreeHelper::createSubjectTreeView($router, $children, $subject->getId());
                }

                $items = $items.sprintf($item, $content);
            }
        }

        return sprintf($tree, $items);
    }

}