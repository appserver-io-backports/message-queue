<?php

/**
 * TechDivision\MessageQueue\Service\Locator\ResourceLocatorInterface
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue\Service\Locator;

use TechDivision\MessageQueueClient\Queue;

/**
 * Interface for the resource locator instances.
 *
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Markus Stockbauer <ms@techdivision.com>
 * @author      Tim Wagner <tw@techdivision.com>
 */
interface ResourceLocatorInterface {

    /**
     * Tries to locate the resource related with the passed queue.
     *
     * @param \TechDivision\MessageQueueClient\Queue $queue
     * @return \TechDivision\MessageQueueClient\Interfaces\MessageReceiver The receiver that handles the passed queue
     */
    public function locate(Queue $queue);
}