<?php
/**
 * TechDivision\MessageQueue\Service\Locator\QueueLocator
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Service\Locator;

use TechDivision\MessageQueueClient\Queue;
use TechDivision\ApplicationServer\InitialContext;
use TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface;

/**
 * The queue resource locator implementation.
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
     */
    public function __construct($queueManager)
    {
        $this->queueManager = $queueManager;
    }

    /**
     * Tries to locate the servlet that handles the request and returns the instance if one can be found.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue client instance
     *
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver
     * @see \TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface::locate()
     */
    public function locate(Queue $queue)
    {
        // load registered queues and requested queue name
        $queues = $this->queueManager->getQueues();
        $queueName = $queue->getName();
   
        // return Receiver of requested queue if available
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
     * @param array  $args      The parameters to pass to the constructor
     *
     * @return object The created instance
     */
    public function newInstance($className, array $args = array())
    {
        return $this->queueManager->newInstance($className, $args);
    }
}
