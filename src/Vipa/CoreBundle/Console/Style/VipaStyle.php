<?php

namespace Vipa\CoreBundle\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class VipaStyle
{
    /**
     * @var SymfonyStyle
     */
    public $io;

    /**
     * VipaStyle constructor.
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
       $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @return SymfonyStyle
     */
    public function getIo()
    {
        return $this->io;
    }

    /**
     * @param SymfonyStyle $io
     *
     * @return $this
     */
    public function setIo($io)
    {
        $this->io = $io;

        return $this;
    }

    public function okSign()
    {
        return '<info>[OK]</info>';
    }

    public function warningSign()
    {
        return '<fg=black;bg=yellow>[WARNING]</>';
    }
}
