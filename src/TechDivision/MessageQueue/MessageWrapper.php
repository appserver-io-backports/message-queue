<?php

/**
 * TechDivision\MessageQueue\MessageWrapper
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

use TechDivision\Storage\GenericStackable;
use TechDivision\MessageQueueProtocol\Queue;
use TechDivision\MessageQueueProtocol\Message;
use TechDivision\MessageQueueProtocol\Monitor;
use TechDivision\MessageQueueProtocol\Utils\PriorityKeys;
use TechDivision\MessageQueueProtocol\Utils\PriorityKey;
use TechDivision\MessageQueueProtocol\Utils\PriorityLow;
use TechDivision\MessageQueueProtocol\Utils\MQStateKeys;
use TechDivision\MessageQueueProtocol\Utils\MQStateKey;
use TechDivision\MessageQueueProtocol\Utils\MQStateActive;

/**
 * This is a simple stackable wrapper for a message.
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */
class MessageWrapper extends GenericStackable implements Message
{

    /**
     * The message ID as hash value.
     *
     * @var string
     */
    protected $messageId = null;

    /**
     * The message itself.
     *
     * @var array
     */
    protected $message = null;

    /**
     * The unique session id.
     *
     * @var string
     */
    protected $sessionId = "";

    /**
     * The destination Queue to send the message to.
     *
     * @var \TechDivision\MessageQueueProtocol\Queue
     */
    protected $destination = null;

    /**
     * The parent message.
     *
     * @var \TechDivision\MessageQueueProtocol\Message
     */
    protected $parentMessage = null;

    /**
     * The monitor for monitoring the message.
     *
     * @var \TechDivision\MessageQueueProtocol\Monitor
     */
    protected $messageMonitor = null;

    /**
     * The priority of the message, defaults to 'low'.
     *
     * @var integer
     */
    protected $priority = PriorityLow::KEY;

    /**
     * The state of the message, defaults to 'active'.
     *
     * @var integer
     */
    protected $state = MQStateActive::KEY;

    /**
     * Creates a new and empty wrapper instance.
     *
     * @return \TechDivision\MessageQueueProtocol\Message The empty message wrapper instance
     */
    public static function emptyInstance()
    {
        return new MessageWrapper();
    }

    /**
     * Initializes the wrapper with the real message
     *
     * @param \TechDivision\MessageQueueProtocol\Message The message we want to wrap
     *
     * @return void
     */
    public function init(Message $message)
    {
        $this->messageId = $message->getMessageId();
        $this->message = $message->getMessage();
        $this->sessionId = $message->getSessionId();
        $this->messageMonitor = $message->getMessageMonitor();
        $this->priority = $message->getPriority()->getPriority();
        $this->state = $message->getState()->getState();
        $this->destination = $message->getDestination();
        $this->sessionId = $message->getSessionId();
    }

    /**
     * Returns the message id.
     *
     * @return string The message id as hash value
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * The message itself.
     *
     * @return array The message itself
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the message as string.
     *
     * @return string The message as string
     */
    public function __toString()
    {
        return serialize($this->message);
    }

    /**
     * Sets the unique session id.
     *
     * @param string $sessionId The uniquid id
     *
     * @return void
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Returns the unique session id.
     *
     * @return string The uniquid id
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Sets the destination Queue.
     *
     * @param \TechDivision\MessageQueueProtocol\Queue $destination The destination
     *
     * @return void
     */
    public function setDestination(Queue $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Returns the destination Queue.
     *
     * @return \TechDivision\MessageQueueProtocol\Queue The destination Queue
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Sets the priority of the message.
     *
     * @param \TechDivision\MessageQueueProtocol\Utils\PriorityKey $priority The priority to set the message to
     *
     * @return void
     */
    public function setPriority(PriorityKey $priority)
    {
        $this->priority = $priority->getPriority();
    }

    /**
     * Returns the priority of the message.
     *
     * @return \TechDivision\MessageQueueProtocol\Utils\PriorityKey The priority of the message
     */
    public function getPriority()
    {
        return PriorityKeys::get($this->priority);
    }

    /**
     * Sets the state of the message.
     *
     * @param \TechDivision\MessageQueueProtocol\Utils\MQStateKey $state The new state
     *
     * @return void
     */
    public function setState(MQStateKey $state)
    {
        $this->state = $state->getState();
    }

    /**
     * Returns the state of the message.
     *
     * @return \TechDivision\MessageQueueProtocol\Utils\MQStateKey The message state
     */
    public function getState()
    {
        return MQStateKeys::get($this->state);
    }

    /**
     * Sets the parent message.
     *
     * @param \TechDivision\MessageQueueProtocol\Message $parentMessage The parent message
     *
     * @return void
     */
    public function setParentMessage(Message $parentMessage)
    {
        $this->parentMessage = $parentMessage;
    }

    /**
     * Returns the parent message.
     *
     * @return \TechDivision\MessageQueueProtocol\Message The parent message
     *
     * @see \TechDivision\MessageQueueProtocol\Message::getParentMessage()
     */
    public function getParentMessage()
    {
        return $this->parentMessage;
    }

    /**
     * Sets the monitor for monitoring the message itself.
     *
     * @param \TechDivision\MessageQueueProtocol\Monitor $messageMonitor The monitor
     *
     * @return void
     */
    public function setMessageMonitor(Monitor $messageMonitor)
    {
        $this->messageMonitor = $messageMonitor;
    }

    /**
     * Returns the message monitor.
     *
     * @return \TechDivision\MessageQueueProtocol\Monitor The monitor
     *
     * @see \TechDivision\MessageQueueProtocol::getMessageMonitor()
     */
    public function getMessageMonitor()
    {
        return $this->messageMonitor;
    }
}
