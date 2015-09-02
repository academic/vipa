<?php

namespace Ojs\JournalBundle\Menu;

use Knp\Menu\FactoryInterface;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\SiteBundle\Acl\AuthorizationChecker;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware
{
    public function leftMenu(FactoryInterface $factory, array $options)
    {
        /**
         * @var Journal $journal
         * @var AuthorizationChecker $checker
         */

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
            // [field, label, options]
            ['submissionSettings', 'title.journal_settings_submission', array('route' => 'ojs_journal_settings_submission')],
            ['mailSettings',       'title.journal_settings_mail',       array('route' => 'ojs_journal_settings_mail')],
            ['checklist',          'title.submission_checklists',       array('route' => 'ojs_journal_checklist_index')],
            ['file',               'title.submission_files',            array('route' => 'ojs_journal_file_index')],
            ['userRole',           'title.users',                       array('route' => 'ojs_journal_user_index')],
            ['index',              'title.journal_indexes',             array('route' => 'ojs_journal_index_index')],
            ['articles',           'title.articles',                    array('route' => 'ojs_journal_article_index')],
            ['sections',           'title.journal_sections',            array('route' => 'ojs_journal_section_index')],
            ['issues',             'title.issues',                      array('route' => 'ojs_journal_issue_index')],
            ['contacts',           'title.contacts',                    array('route' => 'ojs_journal_journal_contact_index')],
            ['issues',             'title.issues',                      array('route' => 'ojs_journal_issue_index')],
            ['mailTemplates',      'title.title.mail_templates',        array('route' => 'ojs_journal_mail_template_index')],
            ['designs',            'title.designs',                     array('route' => 'ojs_journal_design_index')],
            ['theme',              'title.themes',                      array('route' => 'ojs_journal_theme_index')],
            ['boards',             'title.boards',                      array('route' => 'ojs_journal_board_index')],
            ['steps',              'workflow.steps',                    array('route' => 'okul_bilisim_workflow_step_index')],
            ['announcements',      'title.announcements',               array('route' => 'ojs_journal_announcement_index')],
            ['pages',              'title.pages',                       array('route' => 'ojs_journal_page_index')],
            ['posts',              'title.posts',                       array('route' => 'ojs_journal_post_index')],
        ];

        foreach ($items as $item) {
            $item[2]['routeParameters'] = ['journalId' => $journalId];
            if ($checker->isGranted('VIEW', $journal, $item[0])) {
                $menu->addChild($item[1], $item[2]);
            }
        }

        return $menu;
    }
}