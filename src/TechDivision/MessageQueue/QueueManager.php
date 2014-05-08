<?php

/**
 * TechDivision\MessageQueue\QueueManager
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

/**
 * The queue manager handles the queues and message beans registered for the application.
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
class QueueManager
{

    /**
     * The path to the web application.
     *
     * @var string
     */
    protected $webappPath;

    /**
     * The array with queue names and the MessageReceiver class names as values
     *
     * @var array
     */
    protected $queues = array();

    /**
     * Has been automatically invoked by the container after the application
     * instance has been created.
     *
     * @return \TechDivision\ServletContainer\Application The connected application
     */
    public function initialize()
    {

        // deploy the message queues
        $this->registerMessageQueues();

        // return the instance itself
        return $this;
    }

    /**
     * Deploys the MessageQueue's.
     *
     * @param SimpleXMLElement $sxe The XML node with the MessageBean information
     *
     * @return void
     */
    protected function registerMessageQueues()
    {

        if (is_dir($basePath = $this->getWebappPath() . DIRECTORY_SEPARATOR . 'META-INF')) {

            // gather all the deployed web applications
            foreach (new \FilesystemIterator($basePath) as $file) {

                // check if file or sub directory has been found
                if ($file->isDir() === false) {

                    // try to initialize a SimpleXMLElement
                    $sxe = new \SimpleXMLElement($file, null, true);

                    // lookup the MessageQueue's defined in the passed XML node
                    if (($nodes = $sxe->xpath("/message-queues/message-queue")) === false) {
                        continue;
                    }

                    // iterate over all found queues and initialize them
                    foreach ($nodes as $node) {

                        // load the nodes attributes
                        $attributes = $node->attributes();

                        // create a new queue instance
                        $instance = MessageQueue::createQueue((string) $node->destination, (string) $attributes['type']);

                        // register destination and receiver type
                        $this->queues[$instance->getName()] = $instance;
                    }
                }
            }
        }
    }

    /**
     * Returns the array with queue names and the
     * MessageReceiver class names as values.
     *
     * @return array
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     *
     * @param string $webappPath
     */
    public function setWebappPath($webappPath)
    {
        $this->webappPath = $webappPath;
    }

    /**
     *
     * @return string
     */
    public function getWebappPath()
    {
        return $this->webappPath;
    }
}