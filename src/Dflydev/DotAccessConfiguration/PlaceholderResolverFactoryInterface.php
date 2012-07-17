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

interface PlaceholderResolverFactoryInterface
{
    /**
     * Configuration
     * 
     * @param ConfigurationInterface $configuration
     * 
     * @return PlaceholderResolverInterface
     */
    public function create(ConfigurationInterface $configuration);
}
