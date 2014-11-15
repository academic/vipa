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


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateCommandTest extends WebTestCase
{
    /** @var Application */
    private $app;

    public function setup()
    {
        $this->app = new Application($this->createClient()->getKernel());

        $this->app->add(new \Ojs\AnalyticsBundle\Command\UpdateCommand());
    }
    public function testUpdateViewData()
    {
        $command = $this->app->find('ojs:analytics:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'=>$command->getName(),'type'=>'view']);
        $this->assertContains('Successfuslly',$commandTester->getDisplay());
    }

    public function testUpdateDownloadData()
    {
        $command = $this->app->find('ojs:analytics:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'=>$command->getName(),'type'=>'download']);
        $this->assertContains('Successfully',$commandTester->getDisplay());
    }
}
 