<?php
/**
 * User: aybarscengaver
 * Date: 19.11.14
 * Time: 15:23
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\JournalBundle\Tests\Entity;


use Ojs\Common\Tests\BaseTestCase;

class JournalRepositoryTest extends BaseTestCase
{
    public function testBanUser()
    {
        $user = $this->em->find('OjsUserBundle:User', 1);
        $journal = $this->em->find('OjsJournalBundle:Journal', 1);
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        $this->assertTrue($journalRepo->banUser($user, $journal));
    }

    public function testNotPermittedUser()
    {
        $user = $this->em->find('OjsUserBundle:User', 1);
        $journal = $this->em->find('OjsJournalBundle:Journal', 1);
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        $this->assertFalse($journalRepo->checkUserPermit($user,$journal));
    }
    public function testRemoveBanUser()
    {
        $user = $this->em->find('OjsUserBundle:User', 1);
        $journal = $this->em->find('OjsJournalBundle:Journal', 1);
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        $this->assertTrue($journalRepo->removeBannedUser($user, $journal));
    }

    public function testPermittedUser(){
        $user = $this->em->find('OjsUserBundle:User', 1);
        $journal = $this->em->find('OjsJournalBundle:Journal', 1);
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        $this->assertTrue($journalRepo->checkUserPermit($user,$journal));

    }
}
