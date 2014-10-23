<?php

/**
 * TechDivision\MessageQueue\QueueManagerFactory
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
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

use TechDivision\Storage\GenericStackable;
use TechDivision\ApplicationServer\AbstractManagerFactory;

/**
 * A factory for the queue manager instances.
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */
class QueueManagerFactory extends AbstractManagerFactory
{

    /**
     * The main method that creates new instances in a separate context.
     *
     * @return void
     */
    public function run()
    {

        while (true) { // we never stop

            $this->synchronized(function ($self) {

                // make instances local available
                $instances = $self->instances;
                $application = $self->application;
                $initialContext = $self->initialContext;

                // register the default class loader
                $initialContext->getClassLoader()->register(true, true);

                // initialize the stackable for the queues
                $queues = new GenericStackable();

                // initialize the queue locator
                $queueLocator = new QueueLocator();

                // initialize the queue manager
                $queueManager = new QueueManager();
                $queueManager->injectQueues($queues);
                $queueManager->injectWebappPath($application->getWebappPath());
                $queueManager->injectResourceLocator($queueLocator);

                // attach the instance
                $instances[] = $queueManager;

                // wait for the next instance to be created
                $self->wait();

            }, $this);
        }
    }
}
