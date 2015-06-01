<?php

namespace Ojs\Common\Twig;

use Doctrine\ORM\EntityManager;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

class HistoryExtension extends \Twig_Extension
{
    /** @var EntityManager  */
    private $em;

    /** @var \Twig_Environment  */
    private $twig;

    private $template;

    public function __construct(EntityManager $em = null, \Twig_Environment $twig, $template)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            'getLogs' => new \Twig_Function_Method($this, 'getLogs', array('is_safe' => array('html'))),
        );
    }

    public function getLogs($entity)
    {
        return $this->twig->render($this->template, array('logEntities' => $this->logsFromEntity($entity)));
    }
    /**
     * @param $entity
     * @return array
     */
    private function logsFromEntity($entity)
    {
        /** @var LogEntryRepository $repo */
        $repo = $this->em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = array_reverse($repo->getLogEntries($entity));
        $logsArray = array();
        $logLastData = array();
        if (is_array($logs)) {
            foreach ($logs as $log) {
                /** @var LogEntry $log */
                $logRow = new \stdClass();
                $logRow->id = $log->getId();
                $logRow->loggedAt = $log->getLoggedAt();
                $logRow->username = $log->getUsername();
                $logRow->action = $log->getAction();
                $logRow->data = array();
                foreach ($log->getData() as $name => $value) {
                    $dataRow = array('name' => $name, 'old' => null, 'new' => $value);
                    if (isset($logLastData[$name])) {
                        $dataRow['old'] = $logLastData[$name];
                    }
                    $logLastData[$name] = $value;
                    $logRow->data[] = (object) $dataRow;
                }
                $logsArray[] = $logRow;
            }
        } else {
            $logsArray = array();
        }

        return array_reverse($logsArray);
    }

    public function getName()
    {
        return 'history_extension';
    }
}
