<?php

namespace Ojs\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Ojs\AdminBundle\Events\AdminEvents;
use Ojs\CoreBundle\Acl\AuthorizationChecker;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Event\MenuEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder
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
            ['title.users',                     'ojs_admin_user_index',                 'users'],
            ['title.journals',                  'ojs_admin_journal_index',              'newspaper-o'],
            ['title.publishers',                'ojs_admin_publisher_index',            'university'],
            ['title.institutions',              'ojs_admin_institution_index',          'building'],
            ['title.subjects',                  'ojs_admin_subject_index',              'bookmark'],
            ['title.languages',                 'ojs_admin_language_index',             'language'],
            ['title.publisher_types',           'ojs_admin_publisher_type_index',       'building'],
            ['title.article_types',             'ojs_admin_article_type_index',         'file-text'],
            ['title.journal_indexes',           'ojs_admin_index_index',                'sitemap'],
            ['title.journal_application_file',  'ojs_admin_application_file_index',     'file-word-o'],
            ['title.contacts',                  'ojs_admin_contact_index',              'users'],
            ['title.contact_types',             'ojs_admin_contact_type_index',         'user'],
            ['title.mail_templates',             'ojs_admin_mail_template_index',       'envelope']
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
            ['title.post',                      'ojs_admin_post_index',                     'file-o'],
            ['title.pages',                     'ojs_admin_page_index',                     'file-text-o'],
            ['title.announcements',             'ojs_admin_announcement_index',             'bullhorn'],
            ['title.publisher_theme',           'ojs_admin_publisher_theme_index',          'css3'],
            ['title.publisher_design',          'ojs_admin_publisher_design_index',         'wrench'],
            ['title.publisher_managers',        'ojs_admin_publisher_managers_index',       'users'],
            ['title.default_journal_theme',     'ojs_admin_journal_theme_index',            'css3'],
            ['feedback',                        'bulutyazilim_feedback_homepage',            'envelope',         $options['unreadFeedbacks']],
            ['title.system_settings',           'ojs_admin_system_setting_index',           'gears'],
            ['stats',                           'ojs_admin_stats',                          'bar-chart'],
            ['period',                          'ojs_admin_period_index',                   'calendar-check-o'],
            ['title.person_titles',             'ojs_admin_person_title_index',             'user'],
            ['title.files',                     'ojs_admin_file_index',                     'file-image-o']
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
            ['title.publisher_application',     'ojs_admin_application_publisher_index',    'university'],
            ['title.journal_application',       'ojs_admin_application_journal_index',      'newspaper-o'],
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
            $unreadFeedbackCount = 0;
            if(isset($item[3])){
                $unreadFeedbackCount = $item[3];
            }

            $menu->addChild($label, [
                'route' => $path,
                'extras' => [
                    'icon' => $icon,
                    'unreadFeedbackCount' => $unreadFeedbackCount
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
