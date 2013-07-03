<?php

/**
 * TechDivision\MessageQueue\RequestHandler
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue;

use TechDivision\SplClassLoader;
use TechDivision\MessageQueueClient\Interfaces\Message;

/**
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class RequestHandler extends \Worker {

    /**
     * A reference to the container instance.
     * 
     * @var \TechDivision\PersistenceContainer
     */
    protected $container;
    
    /**
     * Array with the available applications.
     * @var array
     */
    protected $applications;

    /**
     * Passes a reference to the container instance.
     * 
     * @param \TechDivision\PersistenceContainer\Container $container The container instance
     * @return void
     */
    public function __construct($container) {
        $this->container = $container;
    }
    
    /**
     * Returns the container instance.
     * 
     * @return \TechDivision\PersistenceContainer\Container The container instance
     */
    public function getContainer() {
        return $this->container;
    }
    
    /**
     * Returns the array with the available applications.
     * 
     * @return array The available applications
     */
    public function getApplications() {
        return $this->applications;
    }
    
    /**
     * Pass the array with the available applications
     * to the worker instance.
     * 
     * @param array $applications The available applications
     */
    public function setApplications($applications) {
        $this->applications = $applications;
    }
    
    /**
     * Tries to find and return the application for the passed class name.
     * 
     * @param string $className The name of the class to find and return the application instance
     * @return \TechDivision\PersistenceContainer\Application The application instance
     * @throws \Exception Is thrown if no application can be found for the passed class name
     */
    public function findApplication($queue) {
        
        // iterate over all classes and check if the application name contains the class name
        foreach ($this->getApplications() as $name => $application) {
            if ($application->hasQueue($queue)) {
                return $application;
            }
        }
        
        // if not throw an exception
        throw new \Exception("Can\'t find application for '" . $queue->getName() . "'");
    }
    
    /**
     * @see \Worker::run()
     */
    public function run() {
        
        // register class loader again, because we are in a thread
        $classLoader = new SplClassLoader();
        $classLoader->register();
        
        // set the applications in the worker instance
        $this->setApplications($this->getContainer()->getApplications());
    }
    
    /**
     * Updates the message monitor.
     * 
     * @param Message $message The message to update the monitor for
     * @return void
     */
    public function updateMonitor(Message $message) {
        error_log("Update message monitor with message: $message");
    }
}