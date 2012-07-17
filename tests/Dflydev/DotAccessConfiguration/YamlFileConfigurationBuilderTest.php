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

class YamlFileConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->markTestSkipped('The Symfony2 YAML library is not available');
        }
    }

    public function testBuilder()
    {
        $configurationBuilder = new YamlFileConfigurationBuilder(array(__DIR__.'/fixtures/yamlFileConfigurationBuilderTest-testBuilder.yml'));

        $configuration = $configurationBuilder->build();

        $this->assertEquals('C', $configuration->get('a.b.c'));
        $this->assertEquals('C0', $configuration->get('a0.b0.c0'));
        $this->assertEquals('C1', $configuration->get('a1.b1.c1'));
        $this->assertEquals(array(
            'yamlFileConfigurationBuilderTest-testBuilder-import-level0.yml',
            '/tmp/testing-this-file-should-not-exist.yml',
            'yamlFileConfigurationBuilderTest-testBuilder-import-level1.yml',
        ), $configuration->get('imports'));
    }
}
