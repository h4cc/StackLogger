StackLogger
==========

Logger [Stack](http://stackphp.com/) middleware that logs Request and Response.

[![Build Status](https://travis-ci.org/h4cc/StackLogger.png)](https://travis-ci.org/h4cc/StackLogger)
[![HHVM Status](http://hhvm.h4cc.de/badge/silpion/stack-logger.png)](http://hhvm.h4cc.de/package/silpion/stack-logger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/h4cc/StackLogger/badges/quality-score.png?s=732f86ff20041606d86bef93a8ad2d5e7c6a3a9f)](https://scrutinizer-ci.com/g/h4cc/StackLogger/)
[![Code Coverage](https://scrutinizer-ci.com/g/h4cc/StackLogger/badges/coverage.png?s=2e687da19667dfab1467bf6f143c6a1742418ce4)](https://scrutinizer-ci.com/g/h4cc/StackLogger/)
[![Project Status](http://stillmaintained.com/h4cc/StackLogger.png)](http://stillmaintained.com/h4cc/StackLogger)


Usage
-----

### Example

Have a look at `example.php` for a example how to use this middleware.


### Options

The following options can be used:

* **logger** (optional): A instance of a PSR-3 compatible logger, like Monolog. Default is a `NullLogger`.

* **log_level** (optional): The log level used for the log entries. Default is LogLevel::INFO.

* **log_sub_request** (optional): Flag if Sub-Requests (HttpKernelInterface::SUB_REQUEST) should be logged. Default is false.


Intallation
-----------

The recommended way to install StackLogger is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "silpion/stack-logger": "@stable"
    }
}
```

**Protip:** you should browse the
[`silpion/stack-logger`](https://packagist.org/packages/silpion/stack-logger)
page to choose a stable version to use, avoid the `@stable` meta constraint.


License
-------

StackLogger is released under the MIT License. See the bundled LICENSE file for details.
