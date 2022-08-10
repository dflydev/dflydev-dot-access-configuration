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

use Dflydev\PlaceholderResolver\RegexPlaceholderResolver;
use PHPUnit\Framework\TestCase;

class PlaceholderResolverFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $configuration = $this->createMock(Configuration::class);
        $placeholderResolverFactory = new PlaceholderResolverFactory();
        $placeholderResolver = $placeholderResolverFactory->create($configuration);

        $this->assertInstanceOf(RegexPlaceholderResolver::class, $placeholderResolver);
    }
}
