<?php
/**
 * TechDivision\MessageQueue\Interfaces\MessageBeanAttribute
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Interfaces
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueue\Interfaces;

/**
 * This is the interface for all MessageBeans 
 * attributes defined in the configuration file.
 *
 * @category   Appserver
 * @package    TechDivision_MessageQueue
 * @subpackage Interfaces
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2013 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */
interface MessageBeanAttribute
{

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
