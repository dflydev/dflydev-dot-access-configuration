<?php

/*
 * This file is a part of dflydev/dot-access-configuration.
 * 
 * (c) Dragonfly Development Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dflydev\DotAccessConfiguration;

class ConfigurationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $configurationFactory = new ConfigurationFactory;
        $configuration = $configurationFactory->create();
    }
}
