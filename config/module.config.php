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
 */
namespace PropelORMModule;

return [
    'console' => [
        'router' => [
            'routes' => [
                'propel-data-migration-down' => [
                    'options' => [
                        'route' => 'propel data-down <namespace> [--fake] [--force]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'data-down'
                        ]
                    ]
                ],
                'propel-data-migration-up' => [
                    'options' => [
                        'route' => 'propel data-up <namespace> [--fake] [--force]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'data-up'
                        ]
                    ]
                ],
                'propel-database-rollup' => [
                    'options' => [
                        'route' => 'propel rollup <namespace> [--mysql-engine=] [--comment=] [--fake] [--validate] [--overwrite]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'rollup'
                        ]
                    ]
                ],
                'propel-database-update' => [
                    'options' => [
                        'route' => 'propel update <namespace> [--mysql-engine=] [--fake] [--validate] [--overwrite]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'update'
                        ]
                    ]
                ],
                'propel-migration-create' => [
                    'options' => [
                        'route' => 'propel create <namespace> [--comment=] [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'create'
                        ]
                    ]
                ],
                'propel-migration-create-data-migration' => [
                    'options' => [
                        'route' => 'propel create-data-migration <namespace> [--comment=] [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'create-data-migration'
                        ]
                    ]
                ],
                'propel-migration-migrate' => [
                    'options' => [
                        'route' => 'propel migrate <namespace> [--fake] [--force]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'migrate'
                        ]
                    ]
                ],
                'propel-migration-data-migrate' => [
                    'options' => [
                        'route' => 'propel data-migrate <namespace> [--fake] [--force]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'data-migrate'
                        ]
                    ]
                ],
                'propel-migration-diff' => [
                    'options' => [
                        'route' => 'propel diff <namespace> [--comment=] [--fake] [--skip-removed-table] [--skip-tables=]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'diff'
                        ]
                    ]
                ],
                'propel-migration-up' => [
                    'options' => [
                        'route' => 'propel up <namespace> [--fake] [--force]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'up'
                        ]
                    ]
                ],
                'propel-migration-down' => [
                    'options' => [
                        'route' => 'propel down <namespace> [--fake] [--force]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'down'
                        ]
                    ]
                ],
                'propel-migration-dump-schema' => [
                    'options' => [
                        'route' => 'propel dump-schema <namespace> [--mysql-engine=] [--comment=] [--fake] [--validate] [--overwrite]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'dump-schema'
                        ]
                    ]
                ],
                'propel-migration-status' => [
                    'options' => [
                        'route' => 'propel status <namespace> [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'status'
                        ]
                    ]
                ],
                'propel-model-build' => [
                    'options' => [
                        'route' => 'propel build <namespace> [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'build'
                        ]
                    ]
                ],
                'propel-sql-build' => [
                    'options' => [
                        'route' => 'propel build-sql <namespace> [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'build-sql'
                        ]
                    ]
                ],
                'propel-version-add' => [
                    'options' => [
                        'route' => 'propel version-add <namespace> --timestamp= [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'version-add'
                        ]
                    ]
                ],
                'propel-version-remove' => [
                    'options' => [
                        'route' => 'propel version-remove <namespace> --timestamp= [--fake]',
                        'defaults' => [
                            'controller' => 'migrateController',
                            'action' => 'version-remove'
                        ]
                    ]
                ],
            ]
        ]
    ]
];
