<?php
/**
 * TechDivision\MessageQueue\Receiver\AbstractReceiver
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
 * @subpackage Receiver
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Receiver;

use TechDivision\ApplicationServer\Interfaces\ContainerInterface;
use TechDivision\ApplicationServer\Interfaces\ApplicationInterface;
use TechDivision\MessageQueueProtocol\Message;
use TechDivision\MessageQueueProtocol\Receiver;

/**
 * The abstract superclass for all receivers.
 *
 * @category   Library
 * @package    TechDivision_MessageQueue
 * @subpackage Receiver
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
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
     * The application instance that provides the entity manager.
     *
     * @var \TechDivision\ApplicationServer\Interfaces\ApplicationInterface
     */
    protected $application;

    /**
     * Initializes the session bean with the Application instance.
     *
     * Checks on every start if the database already exists, if not
     * the database will be created immediately.
     *
     * @param \TechDivision\ApplicationServer\Interfaces\ApplicationInterface $application The application instance
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Returns the application instance.
     *
     * @return \TechDivision\ApplicationServer\Interfaces\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Injects the container in into the receiver instance.
     *
     * @param \TechDivision\ApplicationServer\Interfaces\ContainerInterface $container The container instance
     *
     * @return void
     */
    public function injectContainer(ContainerInterface $container)
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
        if ($this->container == null) {
            throw new \Exception("Can't find container instance");
        }

        // update the monitor
        $this->container->updateMonitor($message);
    }
}
