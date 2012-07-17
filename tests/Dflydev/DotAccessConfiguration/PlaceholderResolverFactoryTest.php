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

class PlaceholderResolverFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $configuration = $this->getMock('Dflydev\DotAccessConfiguration\Configuration');
        $placeholderResolverFactory = new PlaceholderResolverFactory;
        $placeholderResolver = $placeholderResolverFactory->create($configuration);
    }
}
