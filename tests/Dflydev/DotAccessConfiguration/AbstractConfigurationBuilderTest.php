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

class AbstractConfigurationBuilderTest extends TestCase
{
    public function testPlaceholderResolver()
    {
        $placeholderResolver = $this->getMockBuilder(\Dflydev\PlaceholderResolver\PlaceholderResolverInterface::class)->getMock();

        $placeholderResolverFactory = $this->getMockBuilder(\Dflydev\DotAccessConfiguration\PlaceholderResolverFactoryInterface::class)->getMock();
        $placeholderResolverFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($placeholderResolver))
        ;

        $configurationBuilder = $this->getMockForAbstractClass(\Dflydev\DotAccessConfiguration\AbstractConfigurationBuilder::class);
        $configurationBuilder
            ->expects($this->once())
            ->method('internalBuild');

        $configurationBuilder->setPlaceholderResolverFactory($placeholderResolverFactory);
        $configurationBuilder->build();
    }

    public function testReconfigure()
    {
        $configuration000 = $this->getMockBuilder(\Dflydev\DotAccessConfiguration\ConfigurationInterface::class)->getMock();

        $configuration000
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('FOO'))
        ;

        $configuration001 = $this->getMockBuilder(\Dflydev\DotAccessConfiguration\ConfigurationInterface::class)->getMock();

        $configuration001
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->equalTo('bar'))
            ->will($this->returnValue('BAR'))
        ;

        $placeholderResolver = $this->getMockBuilder(\Dflydev\PlaceholderResolver\PlaceholderResolverInterface::class)->getMock();

        $placeholderResolverFactory = $this->getMockBuilder(\Dflydev\DotAccessConfiguration\PlaceholderResolverFactoryInterface::class)->getMock();
        $placeholderResolverFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->will($this->returnValue($placeholderResolver))
        ;

        $configurationFactory = $this->getMockBuilder(\Dflydev\DotAccessConfiguration\ConfigurationFactoryInterface::class)->getMock();
        $configurationFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->will($this->onConsecutiveCalls($configuration000, $configuration001));
        ;

        $configurationBuilder = $this->getMockForAbstractClass('Dflydev\DotAccessConfiguration\AbstractConfigurationBuilder');

        $configurationBuilder->setPlaceholderResolverFactory($placeholderResolverFactory);
        $configurationBuilder->setConfigurationFactory($configurationFactory);

        $reconfiguredConfigurationBuilder = $this->getMockForAbstractClass('Dflydev\DotAccessConfiguration\AbstractConfigurationBuilder');
        $configurationBuilder->reconfigure($reconfiguredConfigurationBuilder);

        $configurationTest000 = $configurationBuilder->build();
        $configurationTest001 = $reconfiguredConfigurationBuilder->build();

        $this->assertEquals('FOO', $configuration000->get('foo'));
        $this->assertEquals('FOO', $configurationTest000->get('foo'));
        $this->assertEquals('BAR', $configuration001->get('bar'));
        $this->assertEquals('BAR', $configurationTest001->get('bar'));
    }
}
