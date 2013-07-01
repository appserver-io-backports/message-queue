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
use TechDivision\MessageQueue\MessageBeanAttributeImpl;

/**
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class Container extends AbstractContainer {
		
	/**
	 * The array with the MessageBeans last invokation time.
	 * @var array
	 */			
	protected $lastInvocation = array();
	
	/**
	 * The array with the MessageBeans initial repetitions.
	 * @var array
	 */			
	protected $initialRepetitions = array();
	
	/**
	 * The array with the MessageBeans repetitions already done.
	 * @var array
	 */			
	protected $repetitions = array();

    /**
     * Returns an array with available applications.
     * 
     * @return \TechDivision\Server The server instance
     * @todo Implement real deployment here
     */
    public function deploy() {

        // gather all the deployed web applications
        foreach (new \FilesystemIterator(getcwd() . '/webapps') as $folder) {

            // check if file or subdirectory has been found
            if (is_dir($folder . DS . 'META-INF')) {

                // initialize the application name
                $name = basename($folder);

                // initialize the application instance
                $application = $this->newInstance('\TechDivision\MessageQueue\Application', array($name));
                $application->setWebappPath($folder->getPathname());

                // connect and add the application to the available applications
                $this->applications[$application->getName()] = $application->connect();
            }
        }

        // return the server instance
        return $this;
    }
}