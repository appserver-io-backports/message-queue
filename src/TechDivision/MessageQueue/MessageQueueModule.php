<?php

/**
 * TechDivision\MessageQueue\MessageQueueModule
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

use TechDivision\Http\HttpRequestInterface;
use TechDivision\Http\HttpResponseInterface;
use TechDivision\ServletEngine\ServletEngine;
use TechDivision\MessageQueue\MessageQueueValve;
use TechDivision\Servlet\Http\HttpServletRequest;

/**
 * A message queue module implementation.
 *
 * @category  Library
 * @package   TechDivision_MessageQueue
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueue
 * @link      http://www.appserver.io
 */
class MessageQueueModule extends ServletEngine
{

    /**
     * The unique module name in the web server context.
     *
     * @var string
     */
    const MODULE_NAME = 'message-queue';

    /**
     * Returns the module name.
     *
     * @return string The module name
     */
    public function getModuleName()
    {
        return MessageQueueModule::MODULE_NAME;
    }

    /**
     * Initialize the valves that handles the requests.
     *
     * @return void
     */
    public function initValves()
    {
        $this->valves[] = new MessageQueueValve();
    }

    /**
     * Initialize the request handlers, instead of the way the servlet engine works
     * we need to initialize a new request handler for each message to avoid locks
     * because of nesting invokation.
     *
     * @return void
     * @see \TechDivision\MessageQueue\MessageQueueModule::requestHandlerFromPool()
     */
    public function initRequestHandlers()
    {
        // we need to create the request handlers on the fly
    }

    /**
     * Tries to find a request handler that matches the actual request and injects it into the request.
     *
     * @param \TechDivision\Servlet\Http\HttpServletRequest $servletRequest The request instance to we have to inject a request handler
     *
     * @return void
     */
    protected function requestHandlerFromPool(HttpServletRequest $servletRequest)
    {

        // iterate over all applications to create a new request handler
        foreach ($this->getApplications() as $pattern => $application) {

            // if the application name matches the servlet context
            if ($application->getName() === ltrim($servletRequest->getContextPath(), '/')) {

                // create a new request handler and inject it into the servlet request
                $requestHandler = new RequestHandler($application);
                $this->workingRequestHandlers[$requestHandler->getThreadId()] = true;
                $servletRequest->injectRequestHandler($requestHandler);
                break;
            }
        }
    }
}
