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

use Dflydev\DotAccessData\Exception\MissingPathException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class YamlFileConfigurationBuilderTest extends TestCase
{
    public function setUp(): void
    {
        if (!class_exists(Yaml::class)) {
            $this->markTestSkipped('The Symfony2 YAML library is not available');
        }
    }

    public function testInvalidBuilder(): void
    {
        $this->expectException(MissingPathException::class);
        
        $configurationBuilder = new YamlFileConfigurationBuilder(
            [__DIR__.'/fixtures/yamlFileConfigurationBuilderTest-testBuilder.yml']
        );

        $configuration = $configurationBuilder->build();

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testValidBuilder(): void
    {
        $this->expectException(MissingPathException::class);

        $configurationBuilder = new YamlFileConfigurationBuilder(
            [__DIR__.'/fixtures/yamlFileConfigurationBuilderTest-testBuilder-import-level0.yml']
        );

        $configuration = $configurationBuilder->build();

        $this->assertInstanceOf(Configuration::class, $configuration);
    }
}
