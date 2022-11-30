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

class YamlConfigurationBuilderTest extends TestCase
{
    public function testBuild()
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->markTestSkipped('The Symfony2 YAML library is not available');
        }

        $configurationBuilder = new YamlConfigurationBuilder();
        $configuration = $configurationBuilder->build();

        $this->assertNotNull($configuration);
    }

    public function testBuildWithData()
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->markTestSkipped('The Symfony2 YAML library is not available');
        }

        $configurationBuilder = new YamlConfigurationBuilder('foo: bar');
        $configuration = $configurationBuilder->build();

        $this->assertNotNull($configuration);
    }
}
