<?php

/**
 * TechDivision\MessageQueue\SocketReceiver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue;

use TechDivision\Socket\Client;

/**
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class SocketReceiver extends \TechDivision\ApplicationServer\SocketReceiver {

    /**
     * @see \TechDivision\ApplicationServer\AbstractReceiver::processRequest()
     */
    public function processRequest(\TechDivision\Socket $socket) {
        
        // create a new client and read one line
        $client = new Client();
        $client->setResource($socket->getResource());

        // read one line and unserialize the passed message
        $message = unserialize($client->readLine());

        // create a new request instance and stack it
        $this->stack($this->newStackable(array($message)));
        
        // close the client connection
        $socket->close();
    }
}