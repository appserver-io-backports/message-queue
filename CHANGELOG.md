# Version 0.9.1

## Bugfixes

* None

## Features

* Revert integration to initialize manager instances with thread based factories
* Add dependency to new appserver-io/logger library
* Integration of monitoring/profiling functionality

# Version 0.9.0

## Bugfixes

* None

## Features

* Integration to initialize manager instances with thread based factories

# Version 0.8.3

## Bugfixes

* Inject all Stackable instances instead of initialize them in QueueManager::__construct => pthreads 2.x compatibility

## Features

* None

# Version 0.8.2

## Bugfixes

* Add synchronized() method around all wait()/notify() calls => pthreads 2.x compatibility

## Features

* None

# Version 0.8.1

## Bugfixes

* None

## Features

* Lookup message beans using InitialContext provided by techdivision/naming package

# Version 0.8.0

## Bugfixes

* None

## Features

* Switch to new techdivision/persistencecontainer version > 0.8

# Version 0.7.3

## Bugfixes

* None

## Features

* Switch to new ClassLoader + ManagerInterface
* Add configuration parameters to manager configuration

# Version 0.7.2

## Bugfixes

* None

## Features

* Bugfix invalid access to local variable $application in AbstractReceiver::updateMonitor() method

# Version 0.7.1

## Bugfixes

* None

## Features

* Refactoring ANT PHPUnit execution process
* Composer integration by optimizing folder structure (move bootstrap.php + phpunit.xml.dist => phpunit.xml)
* Switch to new appserver-io/build build- and deployment environment