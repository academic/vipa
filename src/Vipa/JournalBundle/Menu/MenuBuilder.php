<?php

namespace Vipa\JournalBundle\Menu;

use Knp\Menu\FactoryInterface;
use Vipa\CoreBundle\Acl\AuthorizationChecker;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Event\MenuEvent;
use Vipa\JournalBundle\Event\MenuEvents;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function leftMenu(FactoryInterface $factory)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */
        $dispatcher = $this->container->get('event_dispatcher');
        $checker = $this->container->get('security.authorization_checker');
        $journal = $this->container->get('vipa.journal_service')->getSelectedJournal();
        $journalId = $journal->getId();
        $vipaTwigExtension = $this->container->get('vipa.twig.vipa_extension');

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'nav nav-sidebar');

        if ($checker->isGranted('EDIT', $journal)) {
            $menu->addChild('settings', [
                'route'             => 'vipa_journal_settings_index',
                'routeParameters'   => ['journalId' => $journalId],
            ]);
        }

        $items = [
            // [field, label, route, icon, haveSeperator]
            ['sections',            'title.sections',                       'vipa_journal_section_index',            'folder',       false,  ],
            ['mailTemplate',        'title.mail_templates',                 'vipa_journal_mail_template_index',      'envelope',     false,  ],
            ['index',               'title.journal_indexes',                'vipa_journal_index_index',              'sitemap',      true,   ],

            ['stats',               'dashboard.general_stats',              'vipa_journal_stats_index',              'bar-chart',    true,   ],

            ['userRole',            'title.users',                          'vipa_journal_user_index',               'key',          true,   ],

            ['submissionSettings',  'title.journal_settings_submission',    'vipa_journal_settings_submission',      'paper-plane',  false,  ],
            ['submissionSettings',  'article.types',                        'vipa_article_types_settings_index',     'puzzle-piece', false,  ],
            ['checklist',           'title.submission_checklists',          'vipa_journal_checklist_index',          'list',         false,  ],
            ['file',                'title.submission_files',               'vipa_journal_file_index',               'file',         true,   ],

            ['boards',              'title.boards',                         'vipa_journal_board_index',              'object-group', false,  ],
            ['contacts',            'title.contacts',                       'vipa_journal_journal_contact_index',    'users',        true,   ],

            ['block',               'title.blocks',                         'vipa_journal_block_index',              'th-large',     false,  ],
            ['posts',               'title.post',                           'vipa_journal_post_index',               'file-o',       false,  ],
            ['pages',               'title.pages',                          'vipa_journal_page_index',               'file',         false,  ],
            ['announcements',       'title.announcements',                  'vipa_journal_announcement_index',       'bullhorn',     true,   ],

            ['theme',               'title.themes',                         'vipa_journal_theme_index',              'paint-brush',  false,  ],
            //['design',              'title.designs',                        'vipa_journal_design_index',             'bars',         true,   ],
            //disable design until to stabilized

            ['articles',            'title.articles',                       'vipa_journal_article_index',            'file-text',    false,  ],
            ['issues',              'title.issues',                         'vipa_journal_issue_index',              'newspaper-o',  true,   ],


            ['files',               'title.files',                          'vipa_journal_filemanager_index',        'file-image-o', true,   ],
            ['publisherManager',    'publisher.design',                     'vipa_publisher_manager_design_index',   'wrench',       false,  ],
            ['publisherManager',    'publisher.theme',                      'vipa_publisher_manager_theme_index',    'css3',         false,  ],
            ['publisherManager',    'publisher.edit',                       'vipa_publisher_manager_edit',           'university',   true,   ],
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
                if ($vipaTwigExtension->isGrantedForPublisher()) {
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
        $journal = $this->container->get('vipa.journal_service')->getSelectedJournal();
        $journalId = $journal->getId();

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'nav nav-sidebar');

        $menu->addChild('dashboard', [
            'route' => 'vipa_journal_dashboard_index',
            'routeParameters' => ['journalId' => $journalId],
            'extras' => ['icon' => 'dashboard']
        ]);

        if ($checker->isGranted('CREATE', $journal, 'articles')) {
            $menu->addChild('article.submit', [
                'route' => 'vipa_journal_submission_new',
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


        $journal = $this->container->get('vipa.journal_service')->getSelectedJournal();
        if ($journal) {

            $journalId = $journal->getId();

            if ($checker->isGranted('CREATE', $journal, 'articles')) {

                $menu->addChild('article.submit', [
                    'route' => 'vipa_journal_submission_new',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'file-text']
                ]);
            }

            if ($checker->isGranted('EDIT', $journal, 'userRole')) {

                $menu->addChild('title.users', [
                    'route' => 'vipa_journal_user_index',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'key']
                ]);
            }

            if ($checker->isGranted('EDIT', $journal, 'issues')) {

                $menu->addChild('title.issues', [
                    'route' => 'vipa_journal_issue_index',
                    'routeParameters' => ['journalId' => $journalId],
                    'attributes' => array('data-toggle' => 'tooltip', 'data-placement' => 'left'),
                    'extras' => ['icon' => 'plug']
                ]);
            }

            if ($checker->isGranted('VIEW', $journal, 'articles')) {
                $menu->addChild('articles', [
                    'route' => 'vipa_journal_submission_me',
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
