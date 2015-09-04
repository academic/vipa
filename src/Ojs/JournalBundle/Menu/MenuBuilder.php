<?php

namespace Ojs\JournalBundle\Menu;

use Knp\Menu\FactoryInterface;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Event\MenuEvent;
use Ojs\JournalBundle\JournalEvents;
use Ojs\CoreBundle\Acl\AuthorizationChecker;
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

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'nav nav-sidebar');

        if ($checker->isGranted('EDIT', $journal)) {
            $menu->addChild('settings', [
                'route' => 'ojs_journal_settings_index',
                'routeParameters' => ['journalId' => $journalId]
            ]);
        }

        $items = [
            // [field, label, route, icon]
            ['submissionSettings', 'title.journal_settings_submission', 'ojs_journal_settings_submission',   'paper-plane'],
            ['mailSettings',       'title.journal_settings_mail',       'ojs_journal_settings_mail',         'envelope'],
            ['checklist',          'title.submission_checklists',       'ojs_journal_checklist_index',       'list'],
            ['file',               'title.submission_files',            'ojs_journal_file_index',            'file'],
            ['userRole',           'title.users',                       'ojs_journal_user_index',            'key'],
            ['index',              'title.journals_indexes',            'ojs_journal_index_index',           'sitemap'],
            ['issues',             'title.issues',                      'ojs_journal_issue_index',           'newspaper-o'],
            ['sections',           'title.journal_sections',            'ojs_journal_section_index',         'folder'],
            ['articles',           'title.articles',                    'ojs_journal_article_index',         'file-text'],
            ['contacts',           'title.contacts',                    'ojs_journal_journal_contact_index', 'users'],
            ['mailTemplates',      'title.title.mail_templates',        'ojs_journal_mail_template_index',   'envelope'],
            ['design',             'title.designs',                     'ojs_journal_design_index',          'bars'],
            ['theme',              'title.themes',                      'ojs_journal_theme_index',           'paint-brush'],
            ['boards',             'title.boards',                      'ojs_journal_board_index',           'object-group'],
            ['announcements',      'title.announcements',               'ojs_journal_announcement_index',    'bullhorn'],
            ['pages',              'title.pages',                       'ojs_journal_page_index',            'file'],
            ['posts',              'title.posts',                       'ojs_journal_post_index',            'file-o'],
        ];

        foreach ($items as $item) {
            $field = $item[0];
            $label = $item[1];
            $path  = $item[2];
            $icon  = $item[3];

            if (empty($field) || $checker->isGranted('VIEW', $journal, $field)) {
                $menu->addChild($label, [
                    'route'           => $path,
                    'routeParameters' => ['journalId' => $journalId],
                    'extras'          => ['icon'      => $icon]
                ]);
            }
        }
        $menuEvent = new MenuEvent();
        $menuEvent->setMenuItem($menu);

        $dispatcher->dispatch(JournalEvents::LEFT_MENU_INITIALIZED, $menuEvent);
        return $menuEvent->getMenuItem();
    }
}
