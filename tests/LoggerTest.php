<?php

/**
 * This file is part of the StackLogger package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Psr\Log\LoggerInterface;

use Silpion\Stack\Logger;

/**
 * Tests for Logger.
 * @covers \Silpion\Stack\Logger
 */
class LoggerTest extends PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject | HttpKernelInterface */
    protected $appMock;

    /** @var Response */
    protected $response;

    /** @var \PHPUnit_Framework_MockObject_MockObject | LoggerInterface */
    protected $loggerMock;

    protected function setUp()
    {
        $this->appMock = $this->getMockBuilder('\Symfony\Component\HttpKernel\HttpKernelInterface')
          ->setMethods(array('handle'))
          ->getMockForAbstractClass();

        $this->response = new Response('content', 200);

        $this->appMock->expects($this->any())
          ->method('handle')
          ->will($this->returnValue($this->response));

        $this->loggerMock = $this->getMockBuilder('\Psr\Log\LoggerInterface')
          ->setMethods(array('log'))
          ->getMockForAbstractClass();
    }

    public function testHandleMasterRequest()
    {
        $that = $this;

        $this->loggerMock->expects($this->at(0))
          ->method('log')
          ->will(
            $this->returnCallback(
              function ($logLevel, $logMsg, $logContext) use ($that) {
                  $that->assertEquals(\Psr\Log\LogLevel::INFO, $logLevel);
                  $that->assertEquals('Request "GET /"', $logMsg);
                  $that->assertEquals(
                    array(
                      'request_method',
                      'request_uri',
                      'request_host',
                      'request_port',
                      'request_scheme',
                      'request_client_ip',
                      'request_content_type',
                      'request_acceptable_content_types',
                      'request_etags',
                      'request_charsets',
                      'request_languages',
                      'request_locale',
                      'request_auth_user',
                      'request_auth_has_password',
                      'request_encodings',
                      'request_client_ips',
                    ),
                    array_keys($logContext)
                  );
              }
            )
          );

        $this->loggerMock->expects($this->at(1))
          ->method('log')
          ->will(
            $this->returnCallback(
              function ($logLevel, $logMsg, $logContext) use ($that) {
                  $that->assertEquals(\Psr\Log\LogLevel::INFO, $logLevel);
                  $that->assertEquals('Response 200 for "GET /"', $logMsg);
                  $that->assertEquals(
                    array(
                      'response_status_code',
                      'response_charset',
                      'response_date',
                      'response_etag',
                      'response_expires',
                      'response_last_modified',
                      'response_max_age',
                      'response_protocol_version',
                      'response_ttl',
                      'response_vary',
                    ),
                    array_keys($logContext)
                  );
              }
            )
          );

        $logger = new Logger($this->appMock, array('logger' => $this->loggerMock));

        $logger->handle(Request::create('/'), HttpKernelInterface::MASTER_REQUEST);
    }


    public function testNotHandlingSubRequest()
    {
        $this->loggerMock->expects($this->never())->method('log');

        $logger = new Logger($this->appMock, array('logger' => $this->loggerMock));

        $logger->handle(Request::create('/'), HttpKernelInterface::SUB_REQUEST);
    }

    public function testHandlingSubRequest()
    {
        $this->loggerMock->expects($this->exactly(2))
          ->method('log');

        $logger = new Logger($this->appMock, array('logger' => $this->loggerMock, 'log_sub_request' => true));

        $logger->handle(Request::create('/'), HttpKernelInterface::SUB_REQUEST);
    }
}
