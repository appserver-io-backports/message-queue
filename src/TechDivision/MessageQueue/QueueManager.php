<?php
/**
 * TechDivision\MessageQueue\QueueManager
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
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
=======
 * @category  Appserver
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2013 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueue;

/**
 * The queue manager handles the queues and message beans registered for the application.
 *
<<<<<<< HEAD
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
=======
 * @category  Appserver
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @author    Markus Stockbauer <ms@techdivision.com>
 * @copyright 2013 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
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
<<<<<<< HEAD
=======
     * The application instance
     *
     * @var \TechDivision\MessageQueue\Application
     */
    protected $application;

    /**
     * Initializes the manager with the passed application instance.
     *
     * @param \TechDivision\MessageQueue\Application $application The application instance
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
     * Has been automatically invoked by the container after the application
     * instance has been created.
     *
     * @return \TechDivision\MessageQueue\QueueManager The queue manager
     */
    public function initialize()
    {

        // deploy the message queues
        $this->registerMessageQueues();

        // return the instance itself
        return $this;
    }

    /**
<<<<<<< HEAD
     * Deploys the MessageQueue's.
     *
     * @param SimpleXMLElement $sxe The XML node with the MessageBean information
     *
=======
     * Appends the passed directory to the include path if not already
     * has been appended before.
     *
     * @param string $directory The directory to append
     *
     * @return void
     */
    protected function extendIncludePath($directory)
    {

        // explode the include path
        $includePath = explode(PATH_SEPARATOR, ini_get('include_path'));

        // check if directory has been appended before
        if (in_array($directory, $includePath) === false) {
            ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . $directory);
        }
    }

    /**
     * Deploys the MessageQueue's.
     *
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
     * @return void
     */
    protected function registerMessageQueues()
    {

        if (is_dir($basePath = $this->getWebappPath() . DIRECTORY_SEPARATOR . 'META-INF')) {

            // gather all the deployed web applications
            foreach (new \FilesystemIterator($basePath) as $file) {

                // check if file or sub directory has been found
<<<<<<< HEAD
                if ($file->isDir() === false) {
=======
                if (! is_dir($file) && basename($file) === 'message-queues.xml') {
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b

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
     * Set's the webapp path
     *
<<<<<<< HEAD
     * @param string $webappPath
=======
     * @param String $webappPath The webapp path to set
     *
     * @return void
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
     */
    public function setWebappPath($webappPath)
    {
        $this->webappPath = $webappPath;
    }

    /**
     * Return's the webapp path
     *
     * @return string
     */
    public function getWebappPath()
    {
        return $this->webappPath;
    }
<<<<<<< HEAD
}
=======

    /**
     * Creates a new instance of the passed class name and passes the
     * args to the instance constructor.
     *
     * @param string $className The class name to create the instance of
     * @param array  $args      The parameters to pass to the constructor
     *
     * @return object The created instance
     */
    public function newInstance($className, array $args = array())
    {
        return $this->application->newInstance($className, $args);
    }
}
>>>>>>> 0a313ccad73381fa07933b6fb5a3d8b3d6c76d5b
