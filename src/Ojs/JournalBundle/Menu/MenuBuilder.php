<?php

namespace Ojs\JournalBundle\Menu;

use Knp\Menu\FactoryInterface;
use Ojs\CoreBundle\Acl\AuthorizationChecker;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Event\MenuEvent;
use Ojs\JournalBundle\Event\MenuEvents;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware
{
    public function leftMenu(FactoryInterface $factory)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */
        $dispatcher = $this->container->get('event_dispatcher');
        $checker = $this->container->get('security.authorization_checker');
        $journal = $this->container->get('ojs.journal_service')->getSelectedJournal();
        $journalId = $journal->getId();
        $ojsTwigExtension = $this->container->get('ojs.twig.ojs_extension');

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'nav nav-sidebar');

        if ($checker->isGranted('EDIT', $journal)) {
            $menu->addChild('settings', [
                'route'             => 'ojs_journal_settings_index',
                'routeParameters'   => ['journalId' => $journalId],
            ]);
        }

        $items = [
            // [field, label, route, icon, haveSeperator]
            ['sections',            'title.sections',                       'ojs_journal_section_index',            'folder',       false,  ],
            ['mailTemplate',        'title.mail_templates',                 'ojs_journal_mail_template_index',      'envelope',     false,  ],
            ['index',               'title.journal_indexes',                'ojs_journal_index_index',              'sitemap',      true,   ],

            ['stats',               'dashboard.general_stats',              'ojs_journal_stats_index',              'bar-chart',    true,   ],

            ['userRole',            'title.users',                          'ojs_journal_user_index',               'key',          true,   ],

            ['submissionSettings',  'title.journal_settings_submission',    'ojs_journal_settings_submission',      'paper-plane',  false,  ],
            ['checklist',           'title.submission_checklists',          'ojs_journal_checklist_index',          'list',         false,  ],
            ['file',                'title.submission_files',               'ojs_journal_file_index',               'file',         true,   ],

            ['boards',              'title.boards',                         'ojs_journal_board_index',              'object-group', false,  ],
            ['contacts',            'title.contacts',                       'ojs_journal_journal_contact_index',    'users',        true,   ],

            ['block',               'title.blocks',                         'ojs_journal_block_index',              'th-large',     false,  ],
            ['posts',               'title.post',                           'ojs_journal_post_index',               'file-o',       false,  ],
            ['pages',               'title.pages',                          'ojs_journal_page_index',               'file',         false,  ],
            ['announcements',       'title.announcements',                  'ojs_journal_announcement_index',       'bullhorn',     true,   ],

            ['theme',               'title.themes',                         'ojs_journal_theme_index',              'paint-brush',  false,  ],
            ['design',              'title.designs',                        'ojs_journal_design_index',             'bars',         true,   ],

            ['articles',            'title.articles',                       'ojs_journal_article_index',            'file-text',    false,  ],
            ['issues',              'title.issues',                         'ojs_journal_issue_index',              'newspaper-o',  true,   ],


            ['files',               'title.files',                          'ojs_journal_filemanager_index',        'file-image-o', true,   ],
            ['publisherManager',    'publisher.design',                     'ojs_publisher_manager_design_index',   'wrench',       false,  ],
            ['publisherManager',    'publisher.theme',                      'ojs_publisher_manager_theme_index',    'css3',         false,  ],
            ['publisherManager',    'publisher.edit',                       'ojs_publisher_manager_edit',           'university',   true,   ],
        ];

        foreach ($items as $item) {
            $field = $item[0];
            $label = $item[1];
            $path = $item[2];
            $icon = $item[3];
            $separator = '';
            if($item[4]){
                $separator = 'li-separator';
            }

            if (empty($field) || $checker->isGranted('VIEW', $journal, $field) && $field != 'publisherManager') {
                $menu->addChild($label, [
                    'route' => $path,
                    'routeParameters'   => ['journalId' => $journalId],
                    'attributes'        => ['class' => $separator],
                    'extras'            => ['icon' => $icon]
                ]);
            } elseif ($field == 'publisherManager') {
                if ($ojsTwigExtension->isGrantedForPublisher()) {
                    if($journal->getPublisher() == null || empty($journal->getPublisher())){
                        throw new \LogicException('Journal must have a Publisher. Please, edit this journal from admin -> journal edit page.');
                    }
                    $menu->addChild($label, [
                        'route'             => $path,
                        'routeParameters'   => ['publisherId' => $journal->getPublisher()->getId()],
                        'attributes'        => ['class' => $separator],
                        'extras'            => ['icon' => $icon]
                    ]);
                }
            }

        }
        $menuEvent = new MenuEvent();
        $menuEvent->setMenuItem($menu);

        $dispatcher->dispatch(MenuEvents::LEFT_MENU_INITIALIZED, $menuEvent);
        return $menuEvent->getMenuItem();
    }

    public function topLeftMenu(FactoryInterface $factory)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */
        $dispatcher = $this->container->get('event_dispatcher');
        $checker = $this->container->get('security.authorization_checker');
        $journal = $this->container->get('ojs.journal_service')->getSelectedJournal();
        $journalId = $journal->getId();

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'nav nav-sidebar');

        $menu->addChild('dashboard', [
            'route' => 'ojs_journal_dashboard_index',
            'routeParameters' => ['journalId' => $journalId],
            'extras' => ['icon' => 'dashboard']
        ]);

        if ($checker->isGranted('CREATE', $journal, 'articles')) {
            $menu->addChild('article.submit', [
                'route' => 'ojs_journal_submission_new',
                'routeParameters' => ['journalId' => $journalId],
                'extras' => ['icon' => 'plus-circle',]
            ]);
        }

        $menuEvent = new MenuEvent();
        $menuEvent->setMenuItem($menu);

        $dispatcher->dispatch(MenuEvents::TOP_LEFT_MENU_INITIALIZED, $menuEvent);
        return $menuEvent->getMenuItem();
    }

    public function fabMenu(FactoryInterface $factory)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */
        $dispatcher = $this->container->get('event_dispatcher');
        $checker = $this->container->get('security.authorization_checker');

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'dropdown-menu dropdown-menu-right');

        $menu->addChild('dashboard', [
            'route' => 'dashboard',
            'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
            'extras' => ['icon' => 'dashboard']
        ]);


        $journal = $this->container->get('ojs.journal_service')->getSelectedJournal();
        if ($journal) {

            $journalId = $journal->getId();

            if ($checker->isGranted('CREATE', $journal, 'articles')) {

                $menu->addChild('article.submit', [
                    'route' => 'ojs_journal_submission_new',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'file-text']
                ]);
            }

            if ($checker->isGranted('EDIT', $journal, 'userRole')) {

                $menu->addChild('title.users', [
                    'route' => 'ojs_journal_user_index',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'key']
                ]);
            }

            if ($checker->isGranted('EDIT', $journal, 'issues')) {

                $menu->addChild('title.issues', [
                    'route' => 'ojs_journal_issue_index',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'plug']
                ]);
            }

            if ($checker->isGranted('VIEW', $journal, 'articles')) {
                $menu->addChild('articles', [
                    'route' => 'ojs_journal_submission_me',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'file-o']
                ]);
            }
        }


        $menuEvent = new MenuEvent();
        $menuEvent->setMenuItem($menu);

        $dispatcher->dispatch(MenuEvents::FAB_MENU_INITIALIZED, $menuEvent);

        return $menuEvent->getMenuItem();
    }
}
