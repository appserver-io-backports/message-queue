<?php

/**
 * TechDivision\MessageQueue\Application
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
    
namespace TechDivision\MessageQueue;

use TechDivision\MessageQueue\Service\Locator\QueueLocator;

/**
 * The application instance holds all information about the deployed application
 * and provides a reference to the entity manager and the initial context.
 *
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class Application {

    /**
     * Path to the container's host configuration.
     * @var string
     */
    const CONTAINER_HOST = '/container/host';

    /**
     * Path to the container's base directory.
     * @var string
     */
    const CONTAINER_BASE_DIRECTORY = '/container/baseDirectory';
    
    /**
     * The unique application name.
     * @var string
     */
    protected $name;

    /**
     * The queue manager.
     * @var \TechDivision\MessageQueue\QueueManager
     */
    protected $queueManager;

    /**
     * The host configuration.
     * @var \TechDivision\ApplicationServer\Configuration
     */
    protected $configuration;
    
    /**
     * Passes the application name That has to be the class namespace.
     * 
     * @param type $name The application name
     */
    public function __construct($initialContext, $name) {
        $this->initialContext = $initialContext;
        $this->name = $name;
    }
    
    /**
     * Has been automatically invoked by the container after the application
     * instance has been created.
     * 
     * @return \TechDivision\MessageQueue\Application The connected application
     */
    public function connect() {
        
        // initialize the queue manager instance
        $queueManager = new QueueManager();
        $queueManager->setWebappPath($this->getWebappPath());
        $queueManager->initialize();
        
        // set the queue manager
        $this->setQueueManager($queueManager);
        
        // return the instance itself
        return $this;
    }
    
    /**
     * Returns the application name (that has to be the class namespace, 
     * e. g. TechDivision\Example).
     * 
     * @return string The application name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set's the host configuration.
     *
     * @param \TechDivision\ApplicationServer\Configuration $configuration The host configuration
     * @return \TechDivision\ServletContainer\Application The application instance
     */
    public function setConfiguration($configuration) {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Returns the host configuration.
     *
     * @return \TechDivision\ApplicationServer\Configuration The host configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    /**
     * Returns the path to the appserver webapp base directory.
     *
     * @return string The path to the appserver webapp base directory
     */
    public function getAppBase() {
        $baseDir = $this->getConfiguration()->getChild(self::CONTAINER_BASE_DIRECTORY)->getValue();
        $appBase = $this->getConfiguration()->getChild(self::CONTAINER_HOST)->getAppBase();
        return $baseDir . $appBase;
    }

    /**
     * Return's the path to the web application.
     *
     * @return string The path to the web application
     */
    public function getWebappPath() {
        return $this->getAppBase() . DS . $this->getName();
    }
    
    /**
     * Sets the applications queue manager instance.
     * 
     * @param \TechDivision\MessageQueue\QueueManager $queueManager The queue manager instance
     * @return \TechDivision\MessageQueue\Application The application instance
     */
    public function setQueueManager(QueueManager $queueManager) {
        $this->queueManager = $queueManager;
        return $this;
    }
    
    /**
     * Return the queue manager instance.
     * 
     * @return \TechDivision\MessageQueue\QueueManager The queue manager instance
     */
    public function getQueueManager() {
        return $this->queueManager;
    }
    
    /**
     * Returns TRUE if the application is related with the 
     * passed queue instance.
     * 
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue the application has to be related to
     * @return boolean TRUE if the application is related, else FALSE
     */
    public function hasQueue($queue) {
        return array_key_exists($queue->getName(), $this->getQueueManager()->getQueues());
    }
    
    /**
     * Returns the receiver for the passed queue.
     * 
     * @param \TechDivision\MessageQueueClient\Queue $queue
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver The receiver for the passed queue
     */
    public function locate($queue) {
        $queueLocator = new QueueLocator($this->getQueueManager());
        return $queueLocator->locate($queue);
    }
}