<?php

namespace Vipa\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Vipa\AdminBundle\Events\AdminEvents;
use Vipa\CoreBundle\Acl\AuthorizationChecker;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Event\MenuEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param FactoryInterface $factory
     * @return \Knp\Menu\ItemInterface
     */
    public function adminLeftMenu(FactoryInterface $factory)
    {
        $items = [
            // [ label, route, icon]
            ['title.users',                     'vipa_admin_user_index',                 'users'],
            ['title.journals',                  'vipa_admin_journal_index',              'newspaper-o'],
            ['title.publishers',                'vipa_admin_publisher_index',            'university'],
            ['title.institutions',              'vipa_admin_institution_index',          'building'],
            ['title.subjects',                  'vipa_admin_subject_index',              'bookmark'],
            ['title.languages',                 'vipa_admin_language_index',             'language'],
            ['title.publisher_types',           'vipa_admin_publisher_type_index',       'building'],
            ['title.article_types',             'vipa_admin_article_type_index',         'file-text'],
            ['title.journal_indexes',           'vipa_admin_index_index',                'sitemap'],
            ['title.journal_application_file',  'vipa_admin_application_file_index',     'file-word-o'],
            ['title.contacts',                  'vipa_admin_contact_index',              'users'],
            ['title.contact_types',             'vipa_admin_contact_type_index',         'user'],
            ['title.mail_templates',             'vipa_admin_mail_template_index',       'envelope']
        ];
        return $this->generateMenu($factory, $items, AdminEvents::ADMIN_LEFT_MENU_INITIALIZED);
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function adminRightMenu(FactoryInterface $factory, array $options = array())
    {
        $items = [
            // [field, label, route, icon]
            ['title.post',                      'vipa_admin_post_index',                     'file-o'],
            ['title.pages',                     'vipa_admin_page_index',                     'file-text-o'],
            ['title.announcements',             'vipa_admin_announcement_index',             'bullhorn'],
            ['title.publisher_theme',           'vipa_admin_publisher_theme_index',          'css3'],
            ['title.publisher_design',          'vipa_admin_publisher_design_index',         'wrench'],
            ['title.publisher_managers',        'vipa_admin_publisher_managers_index',       'users'],
            ['title.default_journal_theme',     'vipa_admin_journal_theme_index',            'css3'],
            ['title.system_settings',           'vipa_admin_system_setting_index',           'gears'],
            ['stats',                           'vipa_admin_stats',                          'bar-chart'],
            ['period',                          'vipa_admin_period_index',                   'calendar-check-o'],
            ['title.person_titles',             'vipa_admin_person_title_index',             'user'],
            ['title.files',                     'vipa_admin_file_index',                     'file-image-o']
        ];
        return $this->generateMenu($factory, $items, AdminEvents::ADMIN_RIGHT_MENU_INITIALIZED);
    }

    /**
     * @param FactoryInterface $factory
     * @return \Knp\Menu\ItemInterface
     */
    public function adminApplicationMenu(FactoryInterface $factory)
    {
        $items = [
            // [field, label, route, icon]
            ['title.publisher_application',     'vipa_admin_application_publisher_index',    'university'],
            ['title.journal_application',       'vipa_admin_application_journal_index',      'newspaper-o'],
        ];
        return $this->generateMenu($factory, $items, AdminEvents::ADMIN_APPLICATION_MENU_INITIALIZED);
    }

    /**
     * @param FactoryInterface $factory
     * @param array $items
     * @param null $event
     * @return \Knp\Menu\ItemInterface
     */
    private function generateMenu(FactoryInterface $factory,$items = [], $event = null)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */
        $dispatcher = $this->container->get('event_dispatcher');

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'list-unstyled');

        foreach ($items as $item) {
            $label = $item[0];
            $path = $item[1];
            $icon = $item[2];

            $menu->addChild($label, [
                'route' => $path,
                'extras' => [
                    'icon' => $icon
                ],
            ]);
        }

        $menuEvent = new MenuEvent();
        $menuEvent->setMenuItem($menu);

        if(!is_null($event)){
            $dispatcher->dispatch($event, $menuEvent);
        }
        return $menuEvent->getMenuItem();
    }
}
