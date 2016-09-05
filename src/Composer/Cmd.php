<?php
/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian.feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace sebastianfeldmann\CaptainHook\Composer;

use Composer\Script\Event;
use sebastianfeldmann\CaptainHook\Console\Command\Configuration;
use sebastianfeldmann\CaptainHook\Console\Command\Install;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class Cmd
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class Cmd
{
    /**
     * Gets called by composer after a successful package installation.
     *
     * @param \Composer\Script\Event $event
     * @param string                 $config
     */
    public static function configure(Event $event, $config = null)
    {
        $app           = self::createApplication($event, $config);
        $configuration = new Configuration();
        $configuration->setIO($app->getIO());
        $input         = new ArrayInput(
            ['command' => 'configure', '--configuration' => $config, '-f' => '-f', '-e' => '-e']
        );
        $app->add($configuration);
        $app->run($input);
    }

    /**
     * Installs the hooks to your local repository
     *
     * @param \Composer\Script\Event $event
     * @param string                 $config
     */
    public static function install(Event $event, $config = null)
    {
        $app     = self::createApplication($event, $config);
        $install = new Install();
        $install->setIO($app->getIO());
        $input   = new ArrayInput(['command' => 'install', '--configuration' => $config, '-f' => '-f']);
        $app->add($install);
        $app->run($input);
    }

    /**
     * Create a CaptainHook Composer application.
     *
     * @param  \Composer\Script\Event $event
     * @param  string                 $config
     * @return \sebastianfeldmann\CaptainHook\Composer\Application
     */
    private static function createApplication(Event $event, $config = null)
    {
        $app = new Application();
        $app->setAutoExit(false);
        $app->setConfigFile($config);
        $app->setProxyIO($event->getIO());
        return $app;
    }
}
