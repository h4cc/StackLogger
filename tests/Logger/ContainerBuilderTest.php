<?php

/**
 * This file is part of the StackLogger package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Logger;

use Monolog\Logger;
use Psr\Log\LogLevel;
use Silpion\Stack\Logger\ContainerBuilder;

/**
 * Tests for ContainerBuilder.
 * @covers \Silpion\Stack\Logger\ContainerBuilder
 */
class ContainerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder ObjectUnderTest */
    protected $containerBuilder;

    protected function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
    }

    public function testProcessDefaults()
    {
        $container = $this->containerBuilder->process(new \Pimple(), array());

        $this->assertInstanceOf('\Psr\Log\NullLogger', $container['logger']);
        $this->assertEquals(LogLevel::INFO, $container['log_level']);
        $this->assertFalse($container['log_sub_request']);
    }

    public function testProcessWithOptions()
    {
        $logger = new Logger('test');

        $options = array(
          'logger' => $logger,
          'log_level' => LogLevel::DEBUG,
          'log_sub_request' => true,
        );

        $container = $this->containerBuilder->process(new \Pimple(), $options);

        $this->assertEquals($logger, $container['logger']);
        $this->assertEquals(LogLevel::DEBUG, $container['log_level']);
        $this->assertTrue($container['log_sub_request']);
    }
}
 