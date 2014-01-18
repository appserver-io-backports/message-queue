<?php

/**
 * TechDivision\MessageQueue\Container
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
namespace TechDivision\MessageQueue;

use TechDivision\ApplicationServer\AbstractContainer;
use TechDivision\MessageQueueClient\Interfaces\Message;

/**
 *
 * @package TechDivision\MessageQueue
 * @copyright Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license http://opensource.org/licenses/osl-3.0.php
 *          Open Software License (OSL 3.0)
 * @author Tim Wagner <tw@techdivision.com>
 * @author Johann Zelger <jz@techdivision.com>
 */
class Container extends AbstractContainer
{

    /**
     * Updates the message monitor.
     *
     * @param Message $message
     *            The message to update the monitor for
     * @return void
     */
    public function updateMonitor(Message $message)
    {
        $this->getInitialContext()
            ->getSystemLogger()
            ->info("Update message monitor with message: $message");
    }
}