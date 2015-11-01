<?php

namespace Ojs\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Ojs\AdminBundle\Events\AdminEvents;
use Ojs\CoreBundle\Acl\AuthorizationChecker;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Event\MenuEvent;
use Ojs\JournalBundle\JournalEvents;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware
{
    public function adminMenu(FactoryInterface $factory)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */
        $dispatcher = $this->container->get('event_dispatcher');

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'list-unstyled');

        $items = [
            // [field, label, route, icon]
            ['title.users', 'ojs_admin_user_index', 'users'],
            ['title.journals', 'ojs_admin_journal_index', 'newspaper-o'],
            ['title.publishers', 'ojs_admin_publisher_index', 'university'],
            ['title.institutions', 'ojs_admin_institution_index', 'building'],
            ['title.subjects', 'ojs_admin_subject_index', 'bookmark'],
            ['title.languages', 'ojs_admin_language_index', 'language'],
            ['title.publisher_types', 'ojs_admin_publisher_type_index', 'building'],
            ['title.article_types', 'ojs_admin_article_type_index', 'file-text'],
            ['title.journal_indexes', 'ojs_admin_index_index', 'sitemap'],
            ['title.journal_application_file', 'ojs_admin_application_file_index', 'file-word-o'],
            ['title.contacts', 'ojs_admin_contact_index', 'users'],
            ['title.contact_types', 'ojs_admin_contact_type_index', 'user']
        ];

        foreach ($items as $item) {
            $label = $item[0];
            $path = $item[1];
            $icon = $item[2];

            $menu->addChild($label, [
                'route' => $path,
                'extras' => ['icon' => $icon]
            ]);
        }

        $menuEvent = new MenuEvent();
        $menuEvent->setMenuItem($menu);

        $dispatcher->dispatch(AdminEvents::ADMIN_MENU_INITIALIZED, $menuEvent);
        return $menuEvent->getMenuItem();
    }
}
