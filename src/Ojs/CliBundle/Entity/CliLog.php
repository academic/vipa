<?php

namespace Ojs\CliBundle\Entity;

use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * CliLog
 */
class CliLog implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $output;

    /**
     * @var boolean
     */
    private $isSuccess;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set command
     * @param  string $command
     * @return CliLog
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get output
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set output
     * @param  string $output
     * @return CliLog
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get isSuccess
     * @return boolean
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Set isSuccess
     * @param  boolean $isSuccess
     * @return CliLog
     */
    public function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;

        return $this;
    }
}
