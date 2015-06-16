<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * JournalUsers controller.
 *
 */
class JournalUsersController extends Controller
{
    /**
     * Finds and displays a Users of a Journal with roles
     * @return mixed
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($journal, $ta) {
                $qb->andWhere($ta . '.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );
        $grid = $this->get('grid');
        $gridAction = $this->get('grid_action');
        $grid->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];

        $rowAction[] = $gridAction->showAction('ujr_show', 'id');
        $rowAction[] = $gridAction->editAction('ujr_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ujr_delete', 'id');
        $rowAction[] = $gridAction->sendMailAction('user_send_mail');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $grid->showColumns(['journal.title']);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse(
            'OjsUserBundle:UserJournalRole:index.html.twig',
            [
                'grid' => $grid
            ]
        );
    }
}
