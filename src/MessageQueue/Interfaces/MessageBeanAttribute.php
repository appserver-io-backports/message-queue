<?php

/**
 * TechDivision\MessageQueue\Interfaces\MessageBeanAttribute
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\MessageQueue\Interfaces;

/**
 * This is the interface for all MessageBeans 
 * attributes defined in the configuration file.
 * 
 * @package     TechDivision\MessageQueue
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
interface MessageBeanAttribute {
	
	/**
	 * Returns the attribute's name.
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the attribute's value.
	 * 
	 * @return string
	 */
	public function getValue();
}