<?php
/**
 * TechDivision\MessageQueue\Application
 *
 * PHP version 5
 *
 * @category  Appserver
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2013 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

use TechDivision\ApplicationServer\AbstractApplication;

/**
 * The application instance holds all information about the deployed application
 * and provides a reference to the entity manager and the initial context.
 *
 * @category  Appserver
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2013 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */
class Application extends AbstractApplication
{

    /**
     * The queue manager.
     *
     * @var \TechDivision\MessageQueue\QueueManager
     */
    protected $queueManager;

    /**
     * Has been automatically invoked by the container after the application
     * instance has been created.
     *
     * @return \TechDivision\MessageQueue\Application The connected application
     */
    public function connect()
    {

        // initialize the queue manager instance
        $queueManager = $this->newInstance('TechDivision\MessageQueue\QueueManager', array(
            $this
        ));
        $queueManager->setWebappPath($this->getWebappPath());
        $queueManager->initialize();

        // set the queue manager
        $this->setQueueManager($queueManager);

        // return the instance itself
        return $this;
    }

    /**
     * Sets the applications queue manager instance.
     *
     * @param \TechDivision\MessageQueue\QueueManager $queueManager The queue manager instance
     *
     * @return \TechDivision\MessageQueue\Application The application instance
     */
    public function setQueueManager(QueueManager $queueManager)
    {
        $this->queueManager = $queueManager;
        return $this;
    }

    /**
     * Return the queue manager instance.
     *
     * @return \TechDivision\MessageQueue\QueueManager The queue manager instance
     */
    public function getQueueManager()
    {
        return $this->queueManager;
    }

    /**
     * Returns TRUE if the application is related with the
     * passed queue instance.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue the application has to be related to
     *
     * @return boolean TRUE if the application is related, else FALSE
     */
    public function hasQueue($queue)
    {
        return array_key_exists($queue->getName(), $this->getQueueManager()->getQueues());
    }

    /**
     * Returns the receiver for the passed queue.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue client instance
     *
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver The receiver for the passed queue
     */
    public function locate($queue)
    {
        $queueLocator = $this->newInstance('TechDivision\MessageQueue\Service\Locator\QueueLocator', array(
            $this->getQueueManager()
        ));
        return $queueLocator->locate($queue);
    }
}
