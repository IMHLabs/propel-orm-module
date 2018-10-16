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
 * @uses Interop\Container\ContainerInterface;
 * @uses Zend\ServiceManager\Factory\FactoryInterface;
 * @uses PropelORMModule\Controller\MigrationController;
 */
namespace PropelORMModule\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use PropelORMModule\Controller\MigrationController;

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
class MigrationControllerFactory implements FactoryInterface
{

    /**
     * Invoke
     *
     * @param ContainerInterface $container Container Interface
     * @param string $requestedName Class Name
     * @param array $options Class Options
     * @return void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('configuration');
        $controller = new MigrationController();
        $controller->setConfig($config);
        return $controller;
    }
}
