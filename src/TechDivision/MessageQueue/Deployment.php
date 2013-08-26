<?php

/**
 * TechDivision\MessageQueue\Deployment
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue;

use TechDivision\ApplicationServer\AbstractDeployment;

/**
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class Deployment extends AbstractDeployment {

    /**
     * Returns an array with available applications.
     *
     * @return \TechDivision\Server The server instance
     */
    public function deploy() {

        // the container configuration
        $containerThread = $this->getContainerThread();
        $configuration = $containerThread->getConfiguration();

        // load the host configuration for the path to the web application folder
        $baseDirectory = $configuration->getChild(self::XPATH_CONTAINER_BASE_DIRECTORY)->getValue();
        $appBase = $configuration->getChild(self::XPATH_CONTAINER_HOST)->getAppBase();

        // gather all the deployed web applications
        foreach (new \FilesystemIterator($baseDirectory . $appBase) as $folder) {

            // check if file or subdirectory has been found
            if (is_dir($folder. DIRECTORY_SEPARATOR . 'META-INF')) {

                // initialize the application name
                $name = basename($folder);

                // initialize the application instance
                $application = $containerThread->newInstance('\TechDivision\MessageQueue\Application', array($this->initialContext, $name));
                $application->setConfiguration($configuration);

                // add the application to the available applications
                $this->applications[$application->getName()] = $application->connect();
            }
        }

        // return initialized applications
        return $this;
    }
}