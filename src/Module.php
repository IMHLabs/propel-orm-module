<?php
/**
 * Propel ORM Module
 *
 * Library to provide Propel integration with ZF3
 *
 * PHP Version: 5
 *
 * @category  InMotion
 * @package   PropelORMModule
 * @author    IMH Development <development@inmotionhosting.com>
 * @copyright 2018 Copyright (c) InMotion Hosting
 * @license   https://inmotionhosting.com proprietary
 * @link      https://inmotionhosting.com
 *
 * @uses Zend\Console\Adapter\AdapterInterface;
 * @uses Zend\EventManager\EventInterface;
 * @uses Zend\ModuleManager\Feature\AutoloaderProviderInterface as Autoloader;
 * @uses Zend\ModuleManager\Feature\BootstrapListenerInterface as BootstrapListener;
 * @uses Zend\ModuleManager\Feature\ConfigProviderInterface as Config;
 * @uses Zend\ModuleManager\Feature\ConsoleUsageProviderInterface as ConsoleUsage;
 * @uses Propel\Runtime\Propel;
 * @uses Propel\Runtime\Connection\ConnectionManagerSingle;
 * @uses PropelConfig\Configuration
 */
namespace PropelORMModule;

use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface as Autoloader;
use Zend\ModuleManager\Feature\BootstrapListenerInterface as BootstrapListener;
use Zend\ModuleManager\Feature\ConfigProviderInterface as Config;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface as ConsoleUsage;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use PropelConfig\Configuration;

/**
 * Propel ORM Module
 *
 * Library to provide Propel integration with ZF3
 *
 * PHP Version: 5
 *
 * @category  InMotion
 * @package   PropelORMModule
 * @author    IMH Development <development@inmotionhosting.com>
 * @copyright 2018 Copyright (c) InMotion Hosting
 * @license   https://inmotionhosting.com proprietary
 * @link      https://inmotionhosting.com
 */
class Module implements ConsoleUsage, Config, Autoloader, BootstrapListener
{

    /**
     * Setup propel connections
     *
     * @param EventInterface $event Event Interface
     *
     * @return void
     */
    public function onBootstrap(EventInterface $event)
    {
        $application    = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $config         = new Configuration();
        $config->parseConfigArray($serviceManager->get('config'));
        $namespaces = $config->getNamespaces();
        $serviceContainer = Propel::getServiceContainer();
        foreach ($namespaces as $namespace) {
            $connectionConfig = $config->getConnectionConfig($namespace);
            if ($config->getDsn($namespace)) {
                $serviceContainer->setAdapterClass($connectionConfig['name'], $connectionConfig['adapter']);
                $manager = new ConnectionManagerSingle();
                $manager->setConfiguration([
                    'dsn'      => $config->getDsn($namespace),
                    'user'     => $connectionConfig['username'],
                    'password' => $connectionConfig['password']
                ]);
                $serviceContainer->setConnectionManager($connectionConfig['name'], $manager);
            }
        }
    }

    /**
     * Get Module config
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Get Autoloader config
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/'
                )
            )
        ];
    }
    
    /**
     * Get Controller config
     *
     * @return array
     */
    public function getControllerConfig()
    {
        return [
            'factories' => [
                'migrateController' => 'PropelORMModule\Controller\Factory\MigrationControllerFactory',
            ]
        ];
    }
    
    /**
     * Get Console Usage
     *
     * @param AdapterInterface $console Adapter Interface
     *
     * @return array
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            [
                'php index.php propel data-down [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute down on data migration'
            ],
            [
                'php index.php propel data-up [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute up on data migration'
            ],
            [
                'php index.php propel rollup NAMESPACE',
                'Rollup migrations by deleting all tracked versions and insert the one version that exists'
            ],
            [
                'php index.php propel update [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute pending migrations, build SQL files, build Propel classes'
            ],
            [
                'php index.php propel create NAMESPACE',
                'Create empty migration'
            ],
            [
                'php index.php propel create-data-migration NAMESPACE',
                'Create empty data migration'
            ],
            [
                'php index.php propel migrate [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute all pending migrations'
            ],
            [
                'php index.php propel data-migrate [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute all pending data migrations'
            ],
            [
                'php index.php propel diff NAMESPACE',
                'Generate diff classes'
            ],
            [
                'php index.php propel up [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute migrations up'
            ],
            [
                'php index.php propel down [NAMESPACE|[comma delimited NAMESPACES]',
                'Execute migrations down'
            ],
            [
                'php index.php propel dump-schema NAMESPACE',
                'Dump database schema to a migration'
            ],
            [
                'php index.php propel status NAMESPACE',
                'Get migration status'
            ],
            [
                'php index.php propel build [NAMESPACE|[comma delimited NAMESPACES]',
                'Build the model classes based on Propel XML schemas'
            ],
            [
                'php index.php propel build-sql [NAMESPACE|[comma delimited NAMESPACES]',
                'Build SQL files'
            ],
            [
                'php index.php propel version-add NAMESPACE --timestamp=TIMETAMP',
                'Add version timestamp to migration table'
            ],
            [
                'php index.php propel version-remove NAMESPACE --timestamp=TIMETAMP',
                'Remove version timestamp from migration table'
            ],
        ];
    }
}
