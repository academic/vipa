<?php

namespace Ojs\JournalBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OjsJournalBundle extends Bundle
{
    public function boot()
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $conf = $em->getConfiguration();

        $conf->addFilter(
            'journalfilter',
            'Ojs\JournalBundle\Filter\JournalFilter'
        );

        $em->getFilters()->enable('journalfilter')->setJournalService($this->container->get('ojs.journal_service'));
    }
}
