<?php
/**
 * TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface
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
=======
 * @category   Appserver
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
<<<<<<< HEAD
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
=======
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Service\Locator;

use TechDivision\MessageQueueProtocol\Queue;

/**
 * Interface for the resource locator instances.
 *
<<<<<<< HEAD
 * @category   Library
=======
 * @category   Appserver
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
 * @package    TechDivision_MessageQueue
 * @subpackage Service
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
<<<<<<< HEAD
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueue
=======
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
 * @link       http://www.appserver.io
 */
interface ResourceLocatorInterface
{

    /**
     * Tries to locate the servlet that handles the request and returns the instance if one can be found.
     *
     * @param \TechDivision\MessageQueueProtocol\Queue $queue
     *
<<<<<<< HEAD
     * @return \TechDivision\MessageQueueProtocol\Queue
     * @see \TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface::locate()
=======
     * @param \TechDivision\MessageQueueClient\Queue $queue The queue client instance
     *
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver The receiver that handles the passed queue
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
     */
    public function locate(Queue $queue);
}
