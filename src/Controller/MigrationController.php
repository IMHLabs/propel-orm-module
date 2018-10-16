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
 * @uses Zend\Mvc\Controller\AbstractActionController;
 * @uses PropelConfig\Configuration;
 * @uses Symfony\Component\Console\Application;
 * @uses Propel\Runtime\Propel;
 * @uses PropelConsole\Generator\Command;
 */
namespace PropelORMModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use PropelConfig\Configuration;
use Symfony\Component\Console\Application;
use Propel\Runtime\Propel;
use PropelConsole\Generator\Command;

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
class MigrationController extends AbstractActionController
{
    /**
     * @var Configuration $config Configuration
     */
    protected $config;

    /**
     * Set Propel Config
     *
     * @param array $config
     * @return \PropelORMModule\Controller\MigrationController
     */
    public function setConfig($config = [])
    {
        $this->config  = new Configuration();
        $this->config->parseConfigArray($config);
        return $this;
    }

    /**
     * Get Propel Config
     *
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Perform Data Down Action
     *
     * @return void
     */
    public function dataDownAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'       => 'data-migration:down',
                '--config-dir'  => $migrationConfig['config_path'],
                '--output-dir'  => $migrationConfig['migration_path'],
                '--fake'        => ($request->getParam('fake')) ?: null,
                '--force'       => ($request->getParam('force')) ?: null,
                '--connection'  => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            $result = $this->executeCommand(
                new Command\DataMigrationDownCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Data migrations down executed on ' . $namespace . "\n";
                if ($request->getParam('fake')) {
                    echo " -- Fake request, no actions performed\n";
                }
            }
        }
    }
    
    /**
     * Perform Data Up Action
     *
     * @return void
     */
    public function dataUpAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'       => 'data-migration:up',
                '--config-dir'  => $migrationConfig['config_path'],
                '--output-dir'  => $migrationConfig['migration_path'],
                '--fake'        => ($request->getParam('fake')) ?: null,
                '--force'       => ($request->getParam('force')) ?: null,
                '--connection'  => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            $result = $this->executeCommand(
                new Command\DataMigrationUpCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Data migrations up executed on ' . $namespace . "\n";
                if ($request->getParam('fake')) {
                    echo " -- Fake request, no actions performed\n";
                }
            }
        }
    }
    
    /**
     * Perform Data Rollup Action
     *
     * @return void
     */
    public function rollupAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'database:rollup',
                '--config-dir'      => $migrationConfig['config_path'],
                '--schema-dir'      => $migrationConfig['schema_path'],
                '--output-dir'      => $migrationConfig['sql_path'],
                '--migration-dir'   => $migrationConfig['migration_path'],
                '--schema-name'     => $namespace,
                '--validate'        => ($request->getParam('validate')) ?: null,
                '--overwrite'       => ($request->getParam('overwrite')) ?: null,
                '--mysql-engine'    => ($request->getParam('mysql-engine')) ?: null,
                '--comment'         => ($request->getParam('comment')) ?: null,
                '--connection'  => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Database rollup executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\DatabaseRollupCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Database rollup executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Data Rollup Action
     *
     * @return void
     */
    public function updateAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'database:update',
                '--config-dir'      => $migrationConfig['config_path'],
                '--schema-dir'      => $migrationConfig['schema_path'],
                '--output-dir'      => $migrationConfig['class_path'],
                '--sql-dir'         => $migrationConfig['sql_path'],
                '--migration-dir'   => $migrationConfig['migration_path'],
                '--schema-name'     => $namespace,
                '--validate'        => ($request->getParam('validate')) ?: null,
                '--overwrite'       => ($request->getParam('overwrite')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Update executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\DatabaseUpdateCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Update executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Create Migration Action
     *
     * @return void
     */
    public function createAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:create',
                '--config-dir'      => $migrationConfig['config_path'],
                '--schema-dir'      => $migrationConfig['schema_path'],
                '--output-dir'      => $migrationConfig['migration_path'],
                '--comment'         => ($request->getParam('comment')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Migration Create executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            
            $result = $this->executeCommand(
                new Command\MigrationCreateCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Migration Create executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Create Data Migration Action
     *
     * @return void
     */
    public function createDataMigrationAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:create-data-migration',
                '--config-dir'      => $migrationConfig['config_path'],
                '--schema-dir'      => $migrationConfig['schema_path'],
                '--output-dir'      => $migrationConfig['migration_path'],
                '--comment'         => ($request->getParam('comment')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Data Migration Create executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            
            $result = $this->executeCommand(
                new Command\CreateDataMigrationCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Data Migration Create executed on ' . $namespace . "\n";
            }
        }
    }
    
    /**
     * Perform Migration Action
     *
     * @return void
     */
    public function migrateAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:migrate',
                '--config-dir'      => $migrationConfig['config_path'],
                '--output-dir'      => $migrationConfig['migration_path'],
                '--fake'            => ($request->getParam('fake')) ?: null,
                '--force'           => ($request->getParam('force')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Migrations executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            
            $result = $this->executeCommand(
                new Command\MigrationMigrateCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Migrations executed on ' . $namespace . "\n";
            }
        }
    }
    
    /**
     * Perform Data Migration Action
     *
     * @return void
     */
    public function dataMigrateAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:data-migrate',
                '--config-dir'      => $migrationConfig['config_path'],
                '--output-dir'      => $migrationConfig['migration_path'],
                '--fake'            => ($request->getParam('fake')) ?: null,
                '--force'           => ($request->getParam('force')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Migrations executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            
            $result = $this->executeCommand(
                new Command\DataMigrationMigrateCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Migrations executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Generate diff class
     *
     * @return void
     */
    public function diffAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'              => 'migration:diff',
                '--schema-dir'         => $migrationConfig['schema_path'],
                '--config-dir'         => $migrationConfig['config_path'],
                '--output-dir'         => $migrationConfig['migration_path'],
                '--comment'            => ($request->getParam('comment')) ?: null,
                '--skip-removed-table' => ($request->getParam('skip-removed-table')) ?: null,
                '--skip-tables'        => ($request->getParam('skip-tables')) ?: null,
                '--connection'         => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Diff executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\MigrationDiffCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Diff executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Up Action
     *
     * @return void
     */
    public function upAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:up',
                '--config-dir'      => $migrationConfig['config_path'],
                '--output-dir'      => $migrationConfig['migration_path'],
                '--fake'            => ($request->getParam('fake')) ?: null,
                '--force'           => ($request->getParam('force')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Migration Up executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\MigrationUpCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Migration Up executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Up Action
     *
     * @return void
     */
    public function downAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:down',
                '--config-dir'      => $migrationConfig['config_path'],
                '--output-dir'      => $migrationConfig['migration_path'],
                '--fake'            => ($request->getParam('fake')) ?: null,
                '--force'           => ($request->getParam('force')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Migration Down executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\MigrationDownCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Migration Down executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Dump Schema Action
     *
     * @return void
     */
    public function dumpSchemaAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'           => 'migration:dump-schema',
                '--config-dir'      => $migrationConfig['config_path'],
                '--migration-dir'   => $migrationConfig['migration_path'],
                '--schema-dir'      => $migrationConfig['schema_path'],
                '--output-dir'      => $migrationConfig['sql_path'],
                '--schema-name'     => $namespace,
                '--mysql-engine'    => ($request->getParam('mysql-engine')) ?: null,
                '--comment'         => ($request->getParam('comment')) ?: null,
                '--validate'        => ($request->getParam('validate')) ?: null,
                '--overwrite'       => ($request->getParam('overwrite')) ?: null,
                '--connection'      => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Schema Dump executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\DumpSchemaCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Schema Dump executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Status Action
     *
     * @return void
     */
    public function statusAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'          => 'migration:status',
                '--config-dir'     => $migrationConfig['config_path'],
                '--output-dir'     => $migrationConfig['migration_path'],
                '--connection'     => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            $this->executeCommand(
                new Command\MigrationStatusCommand(),
                $inputArray
            );
        }
    }

    /**
     * Perform Build Action
     *
     * @return void
     */
    public function buildAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'          => 'model:build',
                '--config-dir'     => $migrationConfig['config_path'],
                '--schema-dir'     => $migrationConfig['schema_path'],
                '--output-dir'     => $migrationConfig['class_path'],
            ];
            if ($request->getParam('fake')) {
                echo 'Model Build executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\ModelBuildCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Model Build executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Build Sql Action
     *
     * @return void
     */
    public function buildSqlAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(true);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'          => 'sql:build',
                '--config-dir'     => $migrationConfig['config_path'],
                '--schema-dir'     => $migrationConfig['schema_path'],
                '--output-dir'     => $migrationConfig['sql_path'],
            ];
            if ($request->getParam('fake')) {
                echo 'Sql Build executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\SqlBuildCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Sql Build executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Version Add Action
     *
     * @return void
     */
    public function versionAddAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'          => 'version:add',
                '--config-dir'     => $migrationConfig['config_path'],
                '--timestamp'      => $request->getParam('timestamp'),
                '--connection'     => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Version Add Dump executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\VersionAddCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Version Add executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Perform Version Remove Action
     *
     * @return void
     */
    public function versionRemoveAction()
    {
        $request = $this->getRequest();
        $namespaces = $this->getNamespace(false);
        foreach ($namespaces as $namespace) {
            $connectionConfig = $this->getConfig()->getConnectionConfig($namespace);
            $migrationConfig = $this->getConfig()->getMigrationConfig($namespace);
            $inputArray = [
                'command'          => 'version:remove',
                '--config-dir'     => $migrationConfig['config_path'],
                '--timestamp'      => $request->getParam('timestamp'),
                '--connection'     => [
                    sprintf(
                        '%s=%s',
                        $namespace,
                        $this->getConfig()->getDsn($namespace)
                    )
                ],
            ];
            if ($request->getParam('fake')) {
                echo 'Version Remove executed on ' . $namespace . "\n";
                echo " -- Fake request, no actions performed\n";
                exit;
            }
            $result = $this->executeCommand(
                new Command\VersionRemoveCommand(),
                $inputArray
            );
            if (0 == $result) {
                echo 'Version remove executed on ' . $namespace . "\n";
            }
        }
    }

    /**
     * Get Module to update
     *
     * @return void
     */
    protected function getNamespace($allowMultiple = false)
    {
        $return  = [];
        $request = $this->getRequest();
        $namespace = $request->getParam('namespace');
        if (($allowMultiple)&&($namespace == 'all')) {
            $return = $this->getConfig()->getNamespaces();
        } elseif ($allowMultiple) {
            $requestedNamespaces = explode(',', $namespace);
            $return = array_intersect($requestedNamespaces, $this->getConfig()->getNamespaces());
        } elseif (in_array($namespace, $this->getConfig()->getNamespaces())) {
            $return = [ $namespace ];
        }
	if ($return) {
            return $return;
        }
	echo "Invalid namespace provided\n";
        exit;
    }
    
    protected function executeCommand($command, $arguments)
    {
        $app = new Application('Propel', Propel::VERSION);
        $app->add($command);
        foreach ($arguments as $key => $value) {
            if (null === $value) {
                unset($arguments[$key]);
            }
        }
        $input = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $output = new \Symfony\Component\Console\Output\StreamOutput(fopen("php://temp", 'r+'));
        $app->setAutoExit(false);
        $result = $app->run($input, $output);
        rewind($output->getStream());
        echo stream_get_contents($output->getStream());
        return $result;
    }
}
