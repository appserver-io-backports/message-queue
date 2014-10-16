<?php

/**
 * TechDivision\MessageQueue\MessageWrapperFactory
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

/**
 * This a factory implementation to create new message wrapper instances in a protected context.
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */
class MessageWrapperFactory extends \Thread
{

    /**
     * The message wrapper instance we want to return.
     *
     * @return \TechDivision\MessageQueueProtocol\Message
     */
    protected $instance;

    /**
     * Initializes and starts the message wrapper factory.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = MessageWrapper::emptyInstance();
        $this->start();
    }

    /**
     * Creates and returns a new empty message wrapper instance.
     *
     * @return \TechDivision\MessageQueueProtocol\Message $message The empty message wrapper instance
     */
    protected function emptyInstance()
    {

        $this->synchronized(function ($self) { // create the instance
            $self->notify();
        }, $this);

        // return the instance
        return $this->instance;
    }

    /**
     * Create a new instance and wait.
     *
     * @return void
     */
    public function run()
    {
        while (true) {

            // create a new message wrapper instance
            $this->instance = MessageWrapper::emptyInstance();

            $this->synchronized(function ($self) { // and wait
                $this->wait();
            }, $this);
        }
    }
}
