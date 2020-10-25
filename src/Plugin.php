<?php

declare(strict_types=1);

/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */

namespace Vaimo\ChromeDriver;

use Composer\Composer;
use Composer\IO\IOInterface;

class Plugin implements \Composer\Plugin\PluginInterface, \Composer\EventDispatcher\EventSubscriberInterface
{
    /** @var Composer */
    private $composerRuntime;

    /** @var IOInterface */
    private $cliIO;

    public function activate(Composer $composer, IOInterface $cliIO): void
    {
        $this->composerRuntime = $composer;
        $this->cliIO = $cliIO;
    }

    public static function getSubscribedEvents()
    {
        return [
            \Composer\Script\ScriptEvents::POST_INSTALL_CMD => 'installDriver',
            \Composer\Script\ScriptEvents::POST_UPDATE_CMD => 'installDriver',
        ];
    }

    public function installDriver(): void
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

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
