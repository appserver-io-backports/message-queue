<?php

/**
 * TechDivision\MessageQueue\QueueWorker
 *
 * PHP version 5
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

use TechDivision\Naming\InitialContext;
use TechDivision\Storage\GenericStackable;
use TechDivision\MessageQueueProtocol\Message;
use TechDivision\MessageQueueProtocol\QueueContext;
use TechDivision\MessageQueueProtocol\Utils\PriorityKey;
use TechDivision\MessageQueueProtocol\Utils\MQStateActive;
use TechDivision\MessageQueueProtocol\Utils\MQStateFailed;
use TechDivision\MessageQueueProtocol\Utils\MQStateInProgress;
use TechDivision\MessageQueueProtocol\Utils\MQStatePaused;
use TechDivision\MessageQueueProtocol\Utils\MQStateProcessed;
use TechDivision\MessageQueueProtocol\Utils\MQStateToProcess;
use TechDivision\MessageQueueProtocol\Utils\MQStateUnknown;
use TechDivision\Application\Interfaces\ApplicationInterface;
use TechDivision\PersistenceContainerProtocol\BeanContext;

/**
 * A message queue worker implementation listening to a queue, defined in the passed application.
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */
class QueueWorker extends \Thread
{

    /**
     * The application instance the worker is working for.
     *
     * @var \TechDivision\ApplicationServer\Interfaces\ApplicationInterface
     */
    protected $application;

    /**
     * The storage that contains the messages unsorted as they will be attached.
     *
     * @var \TechDivision\Storage\GenericStackable
     */
    protected $storage;

    /**
     * The priority of this queue worker.
     *
     * @var \TechDivision\MessageQueueProtocol\Utils\PriorityKey
     */
    protected $priorityKey;

    /**
     * Initializes the queue worker with the application and the storage it should work on.
     *
     * @param \TechDivision\MessageQueueProtocol\Utils\PriorityKey            $priorityKey The priority of this queue worker
     * @param \TechDivision\ApplicationServer\Interfaces\ApplicationInterface $application The application instance with the queue manager/locator
     *
     * @return void
     */
    public function __construct(PriorityKey $priorityKey, ApplicationInterface $application)
    {

        // bind the worker to the application
        $this->priorityKey = $priorityKey;
        $this->application = $application;

        // initialize the message and priority storage
        $this->storage = new GenericStackable();

        // start the worker
        $this->start();
    }

    /**
     * Attach a new message to the queue.
     *
     * @param \TechDivision\MessageQueueProtocol\Message $message the message to be attached to the queue
     *
     * @return void
     */
    protected function attach(Message $message)
    {

        // add the new message to the message and priority storage
        $this->storage[$message->getMessageId()] = $message;
    }

    /**
     * Removes the message from the queue.
     *
     * @param \TechDivision\MessageQueueProtocol\Message $message The message to be removed from the queue
     *
     * @return void
     */
    protected function remove(Message $message)
    {
        // remove the message from the message
        unset($this->storage[$message->getMessageId()]);
    }

    /**
     * We process the messages here.
     *
     * @return void
     */
    public function run()
    {

        // create a local instance of appication and storage
        $application = $this->application;

        // register the class loader again, because each thread has its own context
        $application->registerClassLoaders();

        /*
         * Reduce CPU load depending on the queues priority, whereas priority
         * can be 1, 2 or 3 actually, so possible values for usleep are:
         *
         * PriorityHigh:         100 === 0.0001 s
         * PriorityMedium:    10.000 === 0.01 s
         * PriorityLow:    1.000.000 === 1 s
         */
        $sleepFor = pow(10, $this->priorityKey->getPriority() * 2);

        while (true) { // run forever

            // iterate over all messages found in the message storage
            foreach ($this->storage as $messageId => $message) {

                // check the message state
                switch ($message->getState()) {

                    case MQStateActive::get(): // message is active and ready to be processed

                        // message is ready to be processed
                        $message->setState(MQStateToProcess::get());
                        break;

                    case MQStatePaused::get(): // message is paused
                    case MQStateInProgress::get(): // message is in progress

                        // do nothing here because everything is OK!
                        break;

                    case MQStateFailed::get(): // message processing has been failure
                    case MQStateProcessed::get(): // message processing has been successfully processed

                        // we remove the message to free the memory
                        $this->remove($message);
                        break;

                    case MQStateToProcess::get(): // message has to be processed now

                        // load class name and session ID from remote method
                        $queueProxy = $message->getDestination();
                        $sessionId = $message->getSessionId();

                        // lookup the queue and process the message
                        if ($queue = $application->getManager(QueueContext::IDENTIFIER)->locate($queueProxy)) {

                            // lock the message
                            $message->setState(MQStateInProgress::get());

                            // the queues receiver type
                            $queueType = $queue->getType();

                            // create an intial context instance
                            $initialContext = new InitialContext();
                            $initialContext->injectApplication($application);

                            // lookup the bean instance
                            $instance = $initialContext->lookup($queueType);

                            // inject the application to the receiver and process the message
                            $instance->onMessage($message, $sessionId);

                            // remove the message from the storage
                            $message->setState(MQStateProcessed::get());
                        }

                        break;

                    case MQStateUnknown::get(): // message is in an unknown state -> this is weired and should never happen!

                        // throw an exception, because this should never happen
                        throw \Exception(sprintf('Message %s has state %s', $messageId, $message->getState()));
                        break;

                    default: // we don't know the message state -> this is weired and should never happen!

                        // throw an exception, because this should never happen
                        throw \Exception(sprintf('Message %s has an invalid state', $messageId));
                        break;
                }

                // reduce CPU load depending on queue priority
                usleep($sleepFor);
            }

            // we maximal check the storage once a second
            sleep(1);
        }
    }
}
