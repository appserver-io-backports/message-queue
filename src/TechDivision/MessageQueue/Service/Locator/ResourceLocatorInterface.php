<?php

/**
 * TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface
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

use TechDivision\MessageQueueProtocol\Queue;

/**
 * Interface for the resource locator instances.
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
interface ResourceLocatorInterface {

    /**
     * Tries to locate the servlet that handles the request and returns the instance if one can be found.
     *
     * @param \TechDivision\MessageQueueProtocol\Queue $queue
     *
     * @return \TechDivision\MessageQueueProtocol\Queue
     * @see \TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface::locate()
     */
    public function locate(Queue $queue);
}