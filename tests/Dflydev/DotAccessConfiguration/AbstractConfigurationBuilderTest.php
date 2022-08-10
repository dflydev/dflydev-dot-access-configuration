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

use Dflydev\PlaceholderResolver\PlaceholderResolverInterface;
use PHPUnit\Framework\TestCase;

class AbstractConfigurationBuilderTest extends TestCase
{
    public function testPlaceholderResolver(): void
    {
        $placeholderResolver = $this->createMock(PlaceholderResolverInterface::class);

        $placeholderResolverFactory = $this->createMock(
            PlaceholderResolverFactoryInterface::class
        );
        $placeholderResolverFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($placeholderResolver);

        $configurationBuilder = $this->getMockForAbstractClass(AbstractConfigurationBuilder::class);
        $configurationBuilder
            ->expects($this->once())
            ->method('internalBuild');

        $configurationBuilder->setPlaceholderResolverFactory($placeholderResolverFactory);
        $configurationBuilder->build();
    }

    public function testReconfigure(): void
    {
        $configuration000 = $this->createMock(ConfigurationInterface::class);

        $configuration000
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->equalTo('foo'))
            ->willReturn('FOO');

        $configuration001 = $this->createMock(ConfigurationInterface::class);

        $configuration001
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->equalTo('bar'))
            ->willReturn('BAR');

        $placeholderResolver = $this->createMock(PlaceholderResolverInterface::class);

        $placeholderResolverFactory = $this->createMock(PlaceholderResolverFactoryInterface::class);
        $placeholderResolverFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->willReturn($placeholderResolver);

        $configurationFactory = $this->createMock(ConfigurationFactoryInterface::class);
        $configurationFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->will($this->onConsecutiveCalls($configuration000, $configuration001));;

        $configurationBuilder = $this->getMockForAbstractClass(AbstractConfigurationBuilder::class);

        $configurationBuilder->setPlaceholderResolverFactory($placeholderResolverFactory);
        $configurationBuilder->setConfigurationFactory($configurationFactory);

        $reconfiguredConfigurationBuilder = $this->getMockForAbstractClass(AbstractConfigurationBuilder::class);
        $configurationBuilder->reconfigure($reconfiguredConfigurationBuilder);

        $configurationTest000 = $configurationBuilder->build();
        $configurationTest001 = $reconfiguredConfigurationBuilder->build();

        $this->assertEquals('FOO', $configuration000->get('foo'));
        $this->assertEquals('FOO', $configurationTest000->get('foo'));
        $this->assertEquals('BAR', $configuration001->get('bar'));
        $this->assertEquals('BAR', $configurationTest001->get('bar'));
    }
}
