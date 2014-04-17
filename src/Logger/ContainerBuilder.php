<?php

/**
 * This file is part of the StackLogger package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Silpion\Stack\Logger;

use Pimple;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * @author Julius Beckmann <beckmann@silpion.de>
 */
class ContainerBuilder
{
    /**
     * Adds services and parameters to given container.
     * ALso applies given options.
     *
     * @param Pimple $container
     * @param array $options
     * @return Pimple
     */
    public function process(Pimple $container, array $options)
    {
        $container['logger'] = $container->share(
          function () {
              return new NullLogger();
          }
        );

        $container['log_level'] = LogLevel::INFO;

        $container['log_sub_request'] = false;

        foreach ($options as $name => $value) {
            $container[$name] = $value;
        }

        return $container;
    }
}
