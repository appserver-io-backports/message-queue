<?php

/**
 * TechDivision\MessageQueue\RequestHandler
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

use TechDivision\Context\Context;
use TechDivision\MessageQueueProtocol\MessageQueueProtocol;
use TechDivision\ApplicationServer\Interfaces\ApplicationInterface;

/**
 * This is a request handler that is necessary to process each request of an
 * application in a separate context.
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */
class RequestHandler extends \Thread implements Context
{

    /**
     * The application instance we're processing requests for.
     *
     * @return \TechDivision\ApplicationServer\Interfaces\ApplicationInterface
     */
    protected $application;

    /**
     * Flag to allow/disallow request handling.
     *
     * @return boolean
     */
    protected $handleRequest;

    /**
     * Initializes the request handler with the application.
     *
     * @return \TechDivision\ApplicationServer\Interfaces\ApplicationInterface The application instance
     */
    public function __construct(ApplicationInterface $application)
    {

        // initialize the request handlers application
        $this->application = $application;
        $this->handleRequest = false;

        // start the request processing
        $this->start();
    }

    /**
     * Returns the value with the passed name from the context.
     *
     * @param string $key The key of the value to return from the context.
     *
     * @return mixed The requested attribute
     */
    public function getAttribute($key)
    {
        // do nothing here, it's only to implement the Context interface
    }

    /**
     * Returns the application instance.
     *
     * @return \TechDivision\ApplicationServer\Interfaces\ApplicationInterface The application instance
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * The main method that handles the thread in a separate context.
     *
     * @return void
     */
    public function run()
    {

        while (true) {

            // synchronize the response data
            $this->synchronized(function ($self) {

                // wait until we've to handle a new request
                $self->wait();

                // check if we've to handle a request
                if ($self->handleRequest) {

                    try {

                        // create a local instance of appication
                        $application = $self->application;

                        // register the class loader again, because each thread has its own context
                        $application->registerClassLoaders();

                        // synchronize the servlet request/response
                        $servletRequest = $self->servletRequest;
                        $servletResponse = $self->servletResponse;

                        // register the class loader again, because each thread has its own context
                        $application->registerClassLoaders();

                        // unpack the message from the request
                        $message = MessageQueueProtocol::unpack($servletRequest->getBodyContent());

                        // load class name and session ID from remote method
                        $queueProxy = $message->getDestination();
                        $sessionId = $message->getSessionId();

                        // lookup the queue and process the message
                        $queue = $application->getQueueManager()->locate($queueProxy);

                        // lock the container and lookup the bean instance
                        $beanManager = $application->getBeanManager();
                        $instance = $beanManager->getResourceLocator()->lookup($beanManager, $queue->getType(), $sessionId, array($application));

                        // inject the application to the receiver and process the message
                        $instance->onMessage($message, $sessionId);

                        // reset the flag
                        $self->handleRequest = false;

                    } catch (\Exception $e) {

                        // we can only write a message here because we don't wait!
                        error_log($e->__toString());
                    }
                }

            }, $this);
        }
    }
}
