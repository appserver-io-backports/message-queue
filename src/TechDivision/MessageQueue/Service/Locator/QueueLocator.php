<?php

/**
 * TechDivision\MessageQueue\Service\Locator\QueueLocator
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue\Service\Locator;

use TechDivision\MessageQueueClient\Queue;
use TechDivision\ApplicationServer\InitialContext;
use TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface;

/**
 * The queue resource locator implementation.
 *
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class QueueLocator implements ResourceLocatorInterface {

    /**
     * The servlet manager instance.
     * @var \TechDivision\MessageQueue\QueueManager
     */
    protected $queueManager;

    /**
     * Initializes the locator with the actual queue manager instance.
     *
     * @param \TechDivision\MessageQueue\QueueManager $queueManager The queue manager instance
     * @return void
     */
    public function __construct($queueManager) {
        $this->queueManager = $queueManager;
    }

    /**
     * Tries to locate the servlet that handles the request and returns the instance if one can be found.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver
     * @see \TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface::locate()
     */
    public function locate(Queue $queue) {
        
        $queues = $this->queueManager->getQueues();
        
        $queueName = $queue->getName();
        
        if (array_key_exists($queueName, $queues)) {
            
            $receiverType = $queues[$queueName];
            
            return $this->newInstance($receiverType);
        }
    }
    
    /**
     * Creates a new instance of the passed class name and passes the
     * args to the instance constructor.
     * 
     * @param string $className The class name to create the instance of
     * @param array $args The parameters to pass to the constructor
     * @return object The created instance
     */
    public function newInstance($className, array $args = array()) { 
        return InitialContext::get()->newInstance($className, $args);
    }
}