<?php

/**
 * TechDivision\MessageQueue\ThreadRequest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
namespace TechDivision\MessageQueue;

use TechDivision\ApplicationServer\AbstractContextThread;
use TechDivision\ApplicationServer\Interfaces\ContainerInterface;
use TechDivision\Socket\Client;
use TechDivision\SplClassLoader;

/**
 * The thread implementation that handles the request.
 *
 * @package TechDivision\MessageQueue
 * @copyright Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license http://opensource.org/licenses/osl-3.0.php
 *          Open Software License (OSL 3.0)
 * @author Johann Zelger <jz@techdivision.com>
 */
class ThreadRequest extends AbstractContextThread
{

    /**
     * The message to process.
     *
     * @var string
     */
    public $message;

    /**
     * Holds the container instance
     *
     * @var ContainerInterface
     */
    public $container;

    /**
     * Initializes the request with the client socket.
     *
     * @param ContainerInterface $container
     *            The ServletContainer
     * @param resource $resource
     *            The client socket instance
     * @return void
     */
    public function init(ContainerInterface $container, $resource)
    {
        $this->container = $container;
        $this->resource = $resource;
    }

    /**
     *
     * @see AbstractThread::main()
     */
    public function main()
    {

        // initialize a new client socket
        $client = $this->newInstance('TechDivision\Socket\Client');

        // set the client socket resource
        $client->setResource($this->resource);

        // read one line and unserialize the passed message
        $message = unserialize($client->readLine());

        try {

            // load class name and session ID from remote method
            $queue = $message->getDestination();
            $sessionId = $message->getSessionId();

            // load the referenced application from the server
            $application = $this->findApplication($queue);

            // lookup the message receiver and process the message
            $receiver = $application->locate($queue);

            // set container to receiver
            $receiver->setContainer($this->container);
            $receiver->onMessage($message, $sessionId);

        } catch (\Exception $e) {
            $this->getInitialContext()
                ->getSystemLogger()
                ->error($e->__toString());
        }

        // try to shutdown client socket
        try {
            $client->shutdown();
            $client->close();

        } catch (\Exception $e) {
            $client->close();
        }

        unset($client);
    }

    /**
     * Returns the container instance.
     *
     * @return \TechDivision\ApplicationServer\Interfaces\ContainerInterface The container instance
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns the array with the available applications.
     *
     * @return array The available applications
     */
    public function getApplications()
    {
        return $this->getContainer()->getApplications();
    }

    /**
     * Tries to find and return the application for the passed class name.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue
     *            The queue to find and return the application instance
     * @return \TechDivision\ApplicationServer\Interfaces\ApplicationInterface The application instance
     * @throws \Exception Is thrown if no application can be found for the passed class name
     */
    public function findApplication($queue)
    {

        // iterate over all classes and check if the application name contains the class name
        foreach ($this->getApplications() as $name => $application) {
            if ($application->hasQueue($queue)) {
                return $application;
            }
        }

        // if not throw an exception
        throw new \Exception("Can\'t find application for '" . $queue->getName() . "'");
    }
}