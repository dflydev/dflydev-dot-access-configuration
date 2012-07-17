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

use Dflydev\PlaceholderResolver\DataSource\DataSourceInterface;
use Dflydev\PlaceholderResolver\RegexPlaceholderResolver;

class PlaceholderResolverFactory extends AbstractPlaceholderResolverFactory
{
    /**
     * {@inheritdocs}
     */
    protected function createInternal(ConfigurationInterface $configuration, DataSourceInterface $dataSource)
    {
        return new RegexPlaceholderResolver($dataSource, '%', '%');
    }
}
