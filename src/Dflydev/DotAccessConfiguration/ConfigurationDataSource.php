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

class ConfigurationDataSource implements DataSourceInterface
{
    private $configuration;

    /**
     * Constructor
     * 
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->setConfiguration($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key, $system = false)
    {
        if ($system) {
            return false;
        }

        return null !== $this->configuration->getRaw($key);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $system = false)
    {
        if ($system) {
            return null;
        }

        return $this->configuration->getRaw($key);
    }

    /**
     * Set Configuration
     * 
     * @param ConfigurationInterface $configuration
     * 
     * @return ConfigurationDataSource
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }
}
