<?php

/**
 * TechDivision\ServletContainer\QueueManager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
    
namespace TechDivision\MessageQueue;

/**
 * The queue manager handles the queues and message beans registered for the application.
 *
 * @package     TechDivision\ServletContainer
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Markus Stockbauer <ms@techdivision.com>
 * @author      Tim Wagner <tw@techdivision.com>
 */
class QueueManager {

    /**
     * The path to the web application.
     * @var string
     */
    protected $webappPath;
	
	/**
	 * The array with queue names and the MessageReceiver class names as values
	 * @var array
	 */
	protected $queues = array();
    
    /**
     * Has been automatically invoked by the container after the application
     * instance has been created.
     * 
     * @return \TechDivision\ServletContainer\Application The connected application
     */
    public function initialize() {
	    
        // deploy the message queues
        $this->registerMessageQueues();
        
        // return the instance itself
        return $this;
    }
    
    /**
     * Appends the passed directory to the include path if not already 
     * has been appended before.
     * 
     * @param string $directory The directory to append
     * @return void
     */
    protected function extendIncludePath($directory) {
         
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
     * @param SimpleXMLElement $sxe The XML node with the MessageBean information
     * @return void
     */
    protected function registerMessageQueues() {
        
        $basePath = $this->getWebappPath() . DIRECTORY_SEPARATOR . 'META-INF';
        
        // gather all the deployed web applications
        foreach (new \FilesystemIterator($basePath) as $file) {
         
            // check if file or subdirectory has been found
            if (!is_dir($file)) {
            
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
                     
                    // extract the attributes from the XML
                    $applicationDirectory = $basePath . DIRECTORY_SEPARATOR . (string) $attributes["directory"];
                    $type = (string) $attributes["type"];
            
                    // add the deployment directory to the include path
                    $this->extendIncludePath($applicationDirectory);
                     
                    $destination = (string) $node->destination;
                    $this->queues[$destination] = $type;
                     
                    error_log("Successfully initialized queue: " . $destination);
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
    public function getQueues() {
        return $this->queues;
    }

    /**
     * @param String $webappPath
     */
    public function setWebappPath($webappPath) {
        $this->webappPath = $webappPath;
    }

    /**
     * @return String
     */
    public function getWebappPath() {
        return $this->webappPath;
    }
}