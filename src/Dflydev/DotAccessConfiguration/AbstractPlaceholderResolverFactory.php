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

abstract class AbstractPlaceholderResolverFactory implements PlaceholderResolverFactoryInterface
{
    /**
     * {@inheritdocs}
     */
    public function create(ConfigurationInterface $configuration)
    {
        return $this->createInternal($configuration, new ConfigurationDataSource($configuration));
    }

    /**
     * Internal create
     * 
     * @param ConfigurationInterface $configuration
     * @param DataSourceInterface $dataSource
     * 
     * @return PlaceholderResolverInterface
     */
    abstract protected function createInternal(ConfigurationInterface $configuration, DataSourceInterface $dataSource);
}
