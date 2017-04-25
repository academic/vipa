<?php

namespace Vipa\CoreBundle\Routing;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;

class ThirdPartyRouteLoader extends Loader
{
    /** @var  KernelInterface */
    private $kernel;

    /** @var  FileLocatorInterface */
    private $fileLocator;

    /**
     * AdvancedLoader constructor.
     * @param KernelInterface $kernel
     * @param FileLocatorInterface $fileLocator
     */
    public function __construct(KernelInterface $kernel, FileLocatorInterface $fileLocator)
    {
        $this->kernel = $kernel;
        $this->fileLocator = $fileLocator;
    }

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        $thirdPartyDir = $this->kernel->getRootDir().'/../thirdparty';
        $fs = new Filesystem();
        if ($fs->exists($thirdPartyDir)) {
            $finder = new Finder();
            $finder->files()->in($thirdPartyDir);

            foreach ($finder as $file) {
                /** @var \Symfony\Component\Finder\SplFileInfo $file */
                $bundleConfig = json_decode(file_get_contents($file->getRealpath()), true);
                if ($bundleConfig) {
                    if (isset($bundleConfig['extra']) && isset($bundleConfig['extra']['bundle-class'])) {
                        if (class_exists($bundleConfig['extra']['bundle-class'])) {
                            $bundleClassParts = explode('\\', $bundleConfig['extra']['bundle-class']);
                            $bundleClassRef = end($bundleClassParts);

                            $resource = '@'.$bundleClassRef.'/Resources/config/routing.yml';
                            $type = 'yaml';

                            try {
                                $this->fileLocator->locate($resource);
                            }
                            catch(\InvalidArgumentException $e){
                                $resource = '@'.$bundleClassRef.'/Resources/config/routing.xml';
                                $type = 'xml';

                                try {
                                    $this->fileLocator->locate($resource);
                                }
                                catch(\InvalidArgumentException $e){
                                    continue;
                                }
                            }

                            $importedRoutes = $this->import($resource, $type);

                            $collection->addCollection($importedRoutes);

                        }
                    }
                }
            }
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'advanced_extra' === $type;
    }
}
