<?php
/**
 * Date: 13.01.15
 * Time: 18:49
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\UserJournalRoleType;

class UserJournalRoleTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new UserJournalRoleType(),
            [
                'userId' => 1,
                'journalId' => 1,
                'roleId' => 1,
            ],
            'Ojs\UserBundle\Entity\UserJournalRole');
    }
}
