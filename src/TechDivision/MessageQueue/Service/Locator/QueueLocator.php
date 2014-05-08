<?php
/**
 * TechDivision\MessageQueue\Service\Locator\QueueLocator
 *
 * PHP version 5
 *
<<<<<<< HEAD
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
=======
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
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
<<<<<<< HEAD
 * @category   Library
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
=======
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
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
<<<<<<< HEAD
    public function __construct(QueueManager $queueManager)
=======
    public function __construct($queueManager)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
    {
        $this->queueManager = $queueManager;
    }

    /**
     * Tries to locate the servlet that handles the request and returns the instance if one can be found.
     *
<<<<<<< HEAD
     * @param \TechDivision\MessageQueueProtocol\Queue $queue
     *
     * @return \TechDivision\MessageQueueProtocol\Queue
=======
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue client instance
     *
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
     * @see \TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface::locate()
     */
    public function locate(Queue $queue)
    {
<<<<<<< HEAD

        // load registered queues and requested queue name
        $queues = $this->queueManager->getQueues();

=======
        // load registered queues and requested queue name
        $queues = $this->queueManager->getQueues();
        $queueName = $queue->getName();
   
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
        // return Receiver of requested queue if available
        if (array_key_exists($queueName = $queue->getName(), $queues)) {
            return $queues[$queueName];
        }
    }
<<<<<<< HEAD
=======
    
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
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
}
