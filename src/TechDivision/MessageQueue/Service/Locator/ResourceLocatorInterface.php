<?php
/**
 * TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Service\Locator;

use TechDivision\MessageQueueClient\Queue;

/**
 * Interface for the resource locator instances.
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */
interface ResourceLocatorInterface
{

    /**
     * Tries to locate the resource related with the passed queue.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue client instance
     *
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver The receiver that handles the passed queue
     */
    public function locate(Queue $queue);
}
