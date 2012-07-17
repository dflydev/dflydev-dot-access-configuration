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

class YamlConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->markTestSkipped('The Symfony2 YAML library is not available');
        }
    }

    public function testBuild()
    {
        $configurationBuilder = new YamlConfigurationBuilder;
        $configuration = $configurationBuilder->build();
    }

    public function testBuildWithData()
    {
        $configurationBuilder = new YamlConfigurationBuilder('foo: bar');
        $configuration = $configurationBuilder->build();
    }
}
