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

class ConfigurationDataSourceTest extends TestCase
{
    public function test()
    {
        $configuration = $this->getMockBuilder(\Dflydev\DotAccessConfiguration\Configuration::class)->getMock();

        $configuration
            ->expects($this->any())
            ->method('getRaw')
            ->will($this->returnValueMap(array(
                array('foo', 'bar'),
                array('foo', null, true),
                array('foo', 'bar', false),
            )))
        ;

        $dataSource = new ConfigurationDataSource($configuration);

        $this->assertEquals('bar', $dataSource->get('foo'));
        $this->assertTrue($dataSource->exists('foo'));
        $this->assertEquals('bar', $dataSource->get('foo', false));
        $this->assertTrue($dataSource->exists('foo', false));
        $this->assertNull($dataSource->get('foo', true));
        $this->assertFalse($dataSource->exists('foo', true));
    }
}
