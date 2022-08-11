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

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class YamlConfigurationBuilderTest extends TestCase
{
    public function setUp(): void
    {
        if (!class_exists(Yaml::class)) {
            $this->markTestSkipped('The Symfony2 YAML library is not available');
        }
    }

    public function testBuild()
    {
        $configurationBuilder = new YamlConfigurationBuilder();
        $configuration = $configurationBuilder->build();

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testBuildWithData()
    {
        $configurationBuilder = new YamlConfigurationBuilder('foo: bar');
        $configuration = $configurationBuilder->build();
        
        $this->assertInstanceOf(Configuration::class, $configuration);
    }
}
