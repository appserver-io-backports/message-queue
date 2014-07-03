<?php

/**
 * TechDivision\MessageQueue\QueueLocator
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

use TechDivision\MessageQueueProtocol\Queue;
use TechDivision\MessageQueue\QueueManager;

/**
 * The queue resource locator implementation.
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
class QueueLocator implements ResourceLocator
{

    /**
     * Tries to locate the queue that handles the request and returns the instance
     * if one can be found.
     *
     * @param \TechDivision\MessageQueue\QueueManager  $queueManager The queue manager instance
     * @param \TechDivision\MessageQueueProtocol\Queue $queue        The queue request
     *
     * @return \TechDivision\MessageQueueProtocol\Queue The requested queue instance
     * @see \TechDivision\MessageQueue\ResourceLocator::locate()
     */
    public function locate(QueueManager $queueManager, Queue $queue)
    {

        // load registered queues and requested queue name
        $queues = $queueManager->getQueues();

        // return Receiver of requested queue if available
        if (array_key_exists($queueName = $queue->getName(), $queues)) {
            return $queues[$queueName];
        }
    }
}
