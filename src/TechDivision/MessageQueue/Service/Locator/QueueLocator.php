<?php

/**
 * TechDivision\MessageQueue\Service\Locator\QueueLocator
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Library
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Service\Locator;

use TechDivision\ApplicationServer\InitialContext;
use TechDivision\MessageQueueProtocol\Queue;
use TechDivision\MessageQueue\QueueManager;
use TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface;

/**
 * The queue resource locator implementation.
 *
 * @category   Library
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
 * @link       http://www.appserver.io
 */
class QueueLocator implements ResourceLocatorInterface
{

    /**
     * The servlet manager instance.
     *
     * @var \TechDivision\MessageQueue\QueueManager
     */
    protected $queueManager;

    /**
     * Initializes the locator with the actual queue manager instance.
     *
     * @param \TechDivision\MessageQueue\QueueManager $queueManager The queue manager instance
     * @return void
     */
    public function __construct(QueueManager $queueManager)
    {
        $this->queueManager = $queueManager;
    }

    /**
     * Tries to locate the servlet that handles the request and returns the instance if one can be found.
     *
     * @param \TechDivision\MessageQueueProtocol\Queue $queue
     *
     * @return \TechDivision\MessageQueueProtocol\Queue
     * @see \TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface::locate()
     */
    public function locate(Queue $queue)
    {

        // load registered queues and requested queue name
        $queues = $this->queueManager->getQueues();

        // return Receiver of requested queue if available
        if (array_key_exists($queueName = $queue->getName(), $queues)) {
            return $queues[$queueName];
        }
    }
}
