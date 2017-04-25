<?php

namespace Vipa\JournalBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VipaJournalBundle extends Bundle
{
    public function boot()
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $conf = $em->getConfiguration();

        $conf->addFilter(
            'journalfilter',
            'Vipa\JournalBundle\Filter\JournalFilter'
        );

        $em->getFilters()->enable('journalfilter')->setJournalService($this->container->get('vipa.journal_service'));
    }
}
