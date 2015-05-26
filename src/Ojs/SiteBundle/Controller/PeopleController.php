<?php

namespace Ojs\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use APY\DataGridBundle\Grid\Source\Vector;

class PeopleController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $source_array = [];
        $em = $this->getDoctrine()->getManager();
        $usersWithRoles = $em->getRepository('OjsUserBundle:UserJournalRole')->getUsersWithRoles(1);

        foreach($usersWithRoles as $user) {
            $journals = array();

            foreach($user['journals'] as $journal) {
                $roles = array();

                foreach($journal['roles'] as $role) {
                    $name = $this->get('translator')->trans($role->getName());
                    $roles[] = $name;
                }

                $journals[] = sprintf("%s (%s)",
                    $journal['entity']->getTitle(),
                    implode(', ', $roles));
            }

            $source_array[] = [
                'id' => $user['id'],
                'username' => $user['entity']->getUsername(),
                'journals' => implode('<br>&bull; ', $journals),
            ];
        }

        $source = new Vector($source_array);
        $grid = $this->get('grid');
        $grid->setSource($source);

        return $grid->getGridResponse('OjsSiteBundle:People:index.html.twig');
    }

    /**
     * @param int $id User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        return $this->render('OjsSiteBundle:People:show.html.twig');
    }
}
