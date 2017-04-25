<?php

namespace Vipa\CoreBundle\Service\FileUploadResolver;

use Jb\Bundle\FileUploaderBundle\Service\Resolver\ResolverInterface;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

class AssetsResolver implements ResolverInterface
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper
     */
    protected $helper;

    /**
     * @var string
     */
    protected $directory;

    /**
     * Constructor
     *
     * @param AssetsHelper $helper
     * @param string $directory
     */
    public function __construct(AssetsHelper $helper, $directory)
    {
        $this->helper = $helper;
        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($key)
    {
        return $this->helper->getUrl(
            trim($this->directory, '/') . '/' . $key
        );
    }
}
