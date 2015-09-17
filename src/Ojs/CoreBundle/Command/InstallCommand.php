<?php

namespace Ojs\CoreBundle\Command;

use Composer\Script\CommandEvent;
use Ojs\AdminBundle\Entity\AdminPage;
use Ojs\CoreBundle\Acl\JournalRoleSecurityIdentity;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalTheme;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class InstallCommand extends ContainerAwareCommand
{
    public static function composer(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];
        $webDir = $options['symfony-web-dir'];

        if (!is_dir($webDir)) {
            echo 'The symfony-web-dir ('.$webDir.') specified in composer.json was not found in '.getcwd(
                ).', can not install assets.'.PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'ojs:install');
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(
            array(
                'symfony-app-dir' => 'app',
                'symfony-web-dir' => 'web',
                'symfony-assets-install' => 'hard',
            ),
            $event->getComposer()->getPackage()->getExtra()
        );

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(
            function ($type, $buffer) {
                echo $buffer;
            }
        );
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd))
            );
        }
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException(
                'The php executable could not be found, add it to your PATH environment variable and try again'
            );
        }

        return $phpPath;
    }

    protected function configure()
    {
        $this
            ->setName('ojs:install')
            ->setDescription('Ojs first installation')
            ->addOption('no-role', null, InputOption::VALUE_NONE, 'Without role data')
            ->addOption('no-admin', null, InputOption::VALUE_NONE, 'Without admin records')
            ->addOption('no-location', null, InputOption::VALUE_NONE, 'Without location data')
            ->addOption('no-theme', null, InputOption::VALUE_NONE, 'Without themes')
            ->addOption('no-acl', null, InputOption::VALUE_NONE, 'Without ACL Data')
            ->addOption('fix-acl', null, InputOption::VALUE_NONE, 'Fix ACL structure')
            ->addOption('no-page', null, InputOption::VALUE_NONE, 'Without default pages');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';
        $translator = $this->getContainer()->get('translator');

        /** @var HelperSet $helperSet */
        $helperSet = $this->getHelperSet();
        /** @var DialogHelper $dialog */
        $dialog = $helperSet->get('dialog');
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        //$translator->setLocale('tr_TR');
        $output->writeln($this->printWelcome());
        $output->writeln(
            '<info>'.
            $translator->trans('ojs.install.title').
            '</info>'
        );

        if (!$dialog->askConfirmation(
            $output,
            '<question>'.
            $translator->trans('ojs.install.confirm').
            ' (y/n) : </question>',
            true
        )
        ) {
            return;
        }

        $command2 = 'doctrine:schema:update --force';
        $output->writeln('<info>Updating db schema!</info>');
        $application->run(new StringInput($command2));

        if (!$input->getOption('no-location')) {
            $location = $this->getContainer()->get('kernel')->getRootDir(
                ).'/../vendor/okulbilisim/location-bundle/Resources/data/location.sql';
            $locationSql = file_get_contents($location);
            $command3 = 'doctrine:query:sql "'.$locationSql.'"';
            $application->run(new StringInput($command3));
            $output->writeln('Locations inserted.');
        }

        if (!$input->getOption('no-role')) {
            $output->writeln($sb.'Inserting roles to db'.$se);
            $this->insertRoles($output);

            if (!$input->getOption('no-admin')) {
                $admin_username = $dialog->ask(
                    $output,
                    '<info>Set system admin username (admin) : </info>',
                    'admin'
                );
                $admin_email = $dialog->ask(
                    $output,
                    '<info>Set system admin email'.
                    ' (root@localhost.com) : </info>',
                    'root@localhost.com'
                );
                $admin_password = $dialog->ask(
                    $output,
                    '<info>Set system admin password (admin) </info>',
                    'admin'
                );

                $output->writeln($sb.'Inserting system admin user to db'.$se);
                $this->insertAdmin($admin_username, $admin_email, $admin_password);
            }
        }

        if (!$input->getOption('no-theme')) {
            $output->writeln($sb.'Inserting default theme record'.$se);
            $this->insertTheme();
        }

        if (!$input->getOption('no-acl')) {
            $output->writeln($sb.'Inserting default ACL records'.$se);
            $this->insertAcls();
        }

        if ($input->getOption('fix-acl')) {
            $output->writeln($sb.'Fixing ACL Records'.$se);
            $this->fixAcls($output);
        }

        if (!$input->getOption('no-page')) {
            $output->writeln($sb.'Creating default pages'.$se);
            $this->createDefaultPages();
        }

        $output->writeln("\nDONE\n");
        $output->writeln(
            "You can run"
            ." <info>php app/console ojs:install:samples</info> "
            ."to add some sample data.\n"
        );
    }

    protected function printWelcome()
    {
        return '';
    }

    /**
     * @param OutputInterface $output
     */
    public function insertRoles(OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $roles = $this->getContainer()->getParameter('roles');
        $role_repo = $doctrine->getRepository('OjsUserBundle:Role');
        foreach ($roles as $role) {
            $new_role = new Role();
            $check = $role_repo->findOneBy(array('role' => $role['role']));
            if (!empty($check)) {
                $output->writeln(
                    '<error> This role record already exists on db </error> : <info>'.
                    $role['role'].'</info>'
                );
                continue;
            }
            $output->writeln('<info>Added : '.$role['role'].'</info>');
            $new_role->setName($role['desc']);
            $new_role->setRole($role['role']);

            $em->persist($new_role);
        }
        $em->flush();
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     */
    public function insertAdmin($username, $email, $password)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(
            array('email' => $email)
        );
        if (is_null($user)) {
            $user = $em->getRepository('OjsUserBundle:User')->findOneBy(
                array('username' => $username)
            );
        }
        if (is_null($user)) {
            $kernel = $this->getContainer()->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);
            $application->run(
                new StringInput(
                    'fos:user:create --super-admin '.$username.' '.$email.' '.$password
                )
            );
        }
    }

    protected function insertTheme()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $theme = new JournalTheme();
        $theme->setTitle('Ojs');
        $em->persist($theme);
        $em->flush();
    }

    protected function insertAcls()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        /*
         * @var AclChainManager
         */
        $aclManager = $this->getContainer()->get('problematic.acl_manager');

        $classes = [
            'OjsJournalBundle:Journal' => [
                'adminMenu',
                'boards',
                'sections',
                'issues',
                'articles',
                'design',
                'contacts',
                'block',
                'theme',
                'index',
                'checklist',
                'file',
                'mailTemplate',
                'report',
                'userRole',
                'citation',
                'steps',
                'announcements',
                'pages',
                'posts',
                'submissionSettings',
                'mailSettings'
            ],
            
            'OjsUserBundle:User' => null,
            'OjsUserBundle:Role' => null,
            'OjsJournalBundle:Publisher' => null,
            'OjsJournalBundle:Institution' => null,
            'OjsJournalBundle:PublisherTypes' => null,
            'OjsJournalBundle:JournalContact' => null,
            'OjsJournalBundle:ContactTypes' => null,
            'OjsJournalBundle:Index' => null,
            'OjsJournalBundle:JournalApplicationFile' => null,
            'OjsJournalBundle:Author' => null,
            'OjsJournalBundle:PublisherTheme' => null,
            'OjsJournalBundle:Lang' => null,
            'OjsJournalBundle:PublisherDesign' => null,
            'OjsJournalBundle:Citation' => null,
            'OjsJournalBundle:Subject' => null,
            'OjsJournalBundle:ArticleTypes' => null,
            'OjsJournalBundle:Period' => null,
            'OjsJournalBundle:PersonTitle' => null,
            'OjsAdminBundle:SystemSetting' => null,
            'OjsAdminBundle:AdminAnnouncement' => null,
            'OjsAdminBundle:AdminPage' => null,
            'OjsAdminBundle:AdminPost' => null,
            'OjsAdminBundle:PublisherManagers' => null,
        ];
        foreach ($classes as $className => $fields) {
            $realClassName = $em->getRepository($className)->getClassName();
            $aclManager->on($realClassName)->to('ROLE_ADMIN')->permit(MaskBuilder::MASK_OWNER)->save();
            if (is_array($fields) && !empty($fields)) {
                foreach ($fields as $field) {
                    $aclManager->on($realClassName)->field($field)->to('ROLE_ADMIN')->permit(
                        MaskBuilder::MASK_OWNER
                    )->save();
                }
            }
        }
    }

    protected function fixAcls(OutputInterface $output)
    {
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';
        $em = $this->getContainer()->get('doctrine')->getManager();
        $aclManager = $this->getContainer()->get('problematic.acl_manager');
        $viewEdit = (new MaskBuilder())
            ->add('view')
            ->add('edit')->get();
        $viewEditDelete = (new MaskBuilder())
            ->add('view')
            ->add('edit')
            ->add('delete')->get();

        /** @var Journal[] $journals */
        $journals = $em->getRepository('OjsJournalBundle:Journal')->findAll();
        foreach ($journals as $journal) {
            $output->writeln($sb.'Journal fix for : '.$journal->getTitle().$se);
            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('boards')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('sections')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('contacts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('block')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('design')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('theme')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('index')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('checklist')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('file')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('mailTemplate')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('report')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('userRole')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('articles')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit($viewEditDelete)->save();
            $aclManager->on($journal)->field('steps')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('announcements')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($journal)
                ->field('mailSettings')
                ->to(new JournalRoleSecurityIdentity($journal,'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($journal)
                ->field('mailSettings')
                ->to(new JournalRoleSecurityIdentity($journal,'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('adminMenu')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('issues')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('sections')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('block')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('issues')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('pages')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('posts')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('index')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('checklist')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('file')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('mailTemplate')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('articles')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit($viewEditDelete)->save();
            $aclManager->on($journal)->field('announcements')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('mailSettings')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $aclManager->on($journal)->field('submissionSettings')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_AUTHOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('articles')->to(new JournalRoleSecurityIdentity($journal, 'ROLE_AUTHOR'))
                ->permit(MaskBuilder::MASK_CREATE)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_PROOFREADER'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_PROOFREADER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_COPYEDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_COPYEDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_LAYOUT_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_LAYOUT_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $aclManager->on($journal)->field('boards')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('sections')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('contacts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('block')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('design')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('theme')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('index')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('checklist')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('file')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('mailTemplate')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $aclManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
        }

        // Every journal manager and editor must be an author too

        /* @var JournalUser[] $journalUsers */
        $authorRole = $em->getRepository('OjsUserBundle:Role')->findOneBy(['role' => 'ROLE_AUTHOR']);
        $journalUsers = $em
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findByRoles(array('ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR'));

        foreach ($journalUsers as $journalUser) {
            if (!$journalUser->getRoles()->contains($authorRole)) {
                $journalUser->getRoles()->add($authorRole);
                $em->persist($journalUser);
                $output->writeln(
                    $sb.'Author added: '.
                    $journalUser->getUser().' - '.
                    $journalUser->getJournal().$se
                );
            }
        }

        $em->flush();
    }

    protected function createDefaultPages()
    {
        $pages = [
            ['about', 'About', 'About page content goes here.'],
            ['privacy', 'Privacy', 'Privacy page content goes here.'],
            ['faq', 'FAQ', 'A list of frequently answered questions goes here.'],
            ['tos', 'Terms of Service', 'TOS page content goes here.'],
        ];

        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($pages as $page) {
            $entity = $em->getRepository('OjsAdminBundle:AdminPage')->findOneBy(['slug' => $page[0]]);

            if (!$entity) {
                $entity = new AdminPage();
                $entity->setCurrentLocale($this->getContainer()->getParameter('locale'));
                $entity->setSlug($page[0]);
                $entity->setTitle($page[1]);
                $entity->setBody($page[2]);
                $em->persist($entity);
            }
        }

        $em->flush();
    }
}
