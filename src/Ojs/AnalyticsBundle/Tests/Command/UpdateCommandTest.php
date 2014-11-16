<?php
/**
 * User: aybarscengaver
 * Date: 15.11.14
 * Time: 12:40
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\AnalyticsBundle\Tests\Command;


use Ojs\Common\Tests\BaseTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Input\ArrayInput;

class UpdateCommandTest extends BaseTestCase
{
    public function setup()
    {
        parent::setup();
        $this->app->add(new \Ojs\AnalyticsBundle\Command\UpdateCommand());
    }

    public function testUpdateViewData()
    {
        $command = $this->app->find('ojs:analytics:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'type' => 'view']);
        $this->assertContains('Successfully', $commandTester->getDisplay());
    }

    public function testUpdateDownloadData()
    {
        $command = $this->app->find('ojs:analytics:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'type' => 'download']);
        $this->assertContains('Successfully', $commandTester->getDisplay());
    }


}
 