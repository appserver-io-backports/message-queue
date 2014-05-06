<?php
/**
 * TechDivision\MessageQueue\Receiver\AbstractReceiver
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Receiver
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Receiver;

use TechDivision\ApplicationServer\Interfaces\ContainerInterface;
use TechDivision\MessageQueueProtocol\Message;
use TechDivision\MessageQueueProtocol\Receiver;

/**
 * The abstract superclass for all receivers.
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Receiver
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */
abstract class AbstractReceiver implements Receiver
{

    /**
     * The Worker that initialized the receiver.
     *
     * @var \TechDivision\ApplicationServer\ContainerInterface
     */
    protected $container = null;

    /**
     * Initializes the receiver with the initializing container.
     *
     * @param \TechDivision\ApplicationServer\ContainerInterface $container The container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Updates the message monitor over the
     * Worker's method.
     *
     * @param \TechDivision\MessageQueueProtocol\Message $message The message to update the monitor for
     *
     * @return void
     * @throws \Exception Is thrown if no Worker exists
     */
    protected function updateMonitor(Message $message)
    {

        // check if a container instance is available
        if (empty($this->container)) {
            throw new \Exception("Necessary Worker does not exist");
        }

        // update the monitor
        $this->container->updateMonitor($message);
    }
}
