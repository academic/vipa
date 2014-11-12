<?php
/** 
 * User: aybarscengaver
 * Date: 12.11.14
 * Time: 09:33
 * Company: Atolye15
 * URI: www.atolye15.com
 * Devs: [
 * 'Emre YILMAZ'=>'emre@atolye15.com',
 * 
 *   ]
 */

namespace Ojs\AnalyticsBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class UpdateCommand extends Command {

    protected function configure()
    {
        $this->setName("ojs:analytics:update")
            ->setDescription("Analytics total data updater")
            ->addArgument("type",InputArgument::REQUIRED,"What is the type you want to update? [view, download]")
            ;

    }

    protected  function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        switch($type){
            case "view":
                $this->updateViewData();
                break;
            case "download":
                $this->updateDownloadData();
                break;
            default:
                $output->writeln("The value may have only 'view' or 'download'");
                break;
        }

    }

    private  function updateViewData()
    {

    }

    private function updateDownloadData()
    {

    }
}