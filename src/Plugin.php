<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ChromeDriver;

use Composer\Composer;
use Composer\IO\IOInterface;

class Plugin implements \Composer\Plugin\PluginInterface, \Composer\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var Composer
     */
    private $composerRuntime;

    /**
     * @var IOInterface
     */
    private $cliIO;
    
    public function activate(Composer $composer, IOInterface $cliIO)
    {
        $this->composerRuntime = $composer;
        $this->cliIO = $cliIO;
    }

    public static function getSubscribedEvents()
    {
        return array(
            \Composer\Script\ScriptEvents::POST_INSTALL_CMD => 'installDriver',
            \Composer\Script\ScriptEvents::POST_UPDATE_CMD => 'installDriver',
        );
    }

    public function installDriver()
    {
        $driverInstaller = new \Vaimo\WebDriverBinaryDownloader\Installer(
            $this->composerRuntime,
            $this->cliIO
        );
        
        $pluginConfig = new \Vaimo\ChromeDriver\Plugin\Config(
            $this->composerRuntime->getPackage()
        );

        $driverInstaller->executeWithConfig($pluginConfig);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        
    }
}
