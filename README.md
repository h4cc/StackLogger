StackLogger
==========

Logger [Stack](http://stackphp.com/) middleware that logs Request and Response.

[![Build
Status](https://travis-ci.org/h4cc/StackLogger.png)](https://travis-ci.org/h4cc/StackLogger)


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
