<?php

/**
 * TechDivision\MessageQueue\WorkerRequest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue;

use TechDivision\Socket\Client;

/**
 * The stackable implementation that handles the request.
 * 
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class WorkerRequest extends \Stackable {
    
    /**
     * The message to process.
     * @var string
     */
    public $message;
    
    /**
     * Initializes the request with the message to process.
     * 
     * @param object $message The message to process
     * @return void
     */
    public function __construct($message) {
        $this->message = $message;
    }
    
    /**
     * @see \Stackable::run()
     */
    public function run() {

        // check if a worker is available
        if ($this->worker) {

            try {
                
                // make message and worker available in local scope
                $message = $this->message;
                $worker = $this->worker;

                // load class name and session ID from remote method
                $queue = $message->getDestination();
                $sessionId = $message->getSessionId();

                // load the referenced application from the server
                $application = $this->worker->findApplication($queue);

                // lookup the message receiver and process the message
                $receiver = $application->locate($queue);
                $receiver->setWorker($worker);
                $receiver->onMessage($message, $sessionId);

            } catch (\Exception $e) {
                error_log($e->__toString());
            }
        }
    }
}