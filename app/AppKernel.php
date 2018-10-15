<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new AppBundle\AppBundle(),
            new UKMNorge\DesignBundle\UKMDesignBundle(),
            new UKMNorge\RSVPBundle\UKMRSVPBundle(),
            #new UKMNorge\DipBundle\UKMDipBundle(),
            new UKMNorge\UKMDipBundle\UKMDipBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new UKMNorge\SMSBundle\UKMSMSBundle(),
            new UKMNorge\APIBundle\UKMAPIBundle()
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return $_ENV['HOME'].'/cache/symfony/rsvp/'.$this->environment;
    }
    
    public function getLogDir()
    {
        return $_ENV['HOME'].'/logs/symfony/rsvp/'.$this->environment;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
