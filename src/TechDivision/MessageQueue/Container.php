<?php
/**
 * TechDivision\MessageQueue\Container
 *
 * PHP version 5
 *
 * @category  Appserver
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Johann Zelger <jz@techdivision.com>
 * @copyright 2013 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

use TechDivision\ApplicationServer\AbstractContainer;
use TechDivision\MessageQueueClient\Interfaces\Message;

/**
 * Class Container
 *
 * @category  Appserver
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Johann Zelger <jz@techdivision.com>
 * @copyright 2013 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */
class Container extends AbstractContainer
{

    /**
     * Updates the message monitor.
     *
     * @param \TechDivision\MessageQueueClient\Interfaces\Message $message The message to update the monitor for
     *
     * @return void
     */
    public function updateMonitor(Message $message)
    {
        $this->getInitialContext()
            ->getSystemLogger()
            ->info("Update message monitor with message: $message");
    }
}
