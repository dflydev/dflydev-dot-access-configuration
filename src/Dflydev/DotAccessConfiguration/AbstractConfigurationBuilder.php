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

abstract class AbstractConfigurationBuilder implements ConfigurationBuilderInterface
{
    private $configurationFactory;
    private $placeholderResolverFactory;

    /**
     * Set Configuration Factory
     *
     * @param ConfigurationFactoryInterface $configurationFactory
     *
     * @return AbstractConfigurationBuilder
     */
    public function setConfigurationFactory(ConfigurationFactoryInterface $configurationFactory)
    {
        $this->configurationFactory = $configurationFactory;

        return $this;
    }

    /**
     * Configuration Factory
     *
     * @return ConfigurationFactoryInterface
     */
    protected function configurationFactory()
    {
        if (null === $this->configurationFactory) {
            $this->configurationFactory = new ConfigurationFactory;
        }

        return $this->configurationFactory;
    }

    /**
     * {@inheritdocs}
     */
    public function build()
    {
        $configuration = $this->configurationFactory()->create();

        if (null !== $this->placeholderResolverFactory) {
            $placeholderResolver = $this->placeholderResolverFactory->create($configuration);
            $configuration->setPlaceholderResolver($placeholderResolver);
        }

        $this->internalBuild($configuration);

        return $configuration;
    }

    /**
     * Set Placeholder Resolver Factory
     *
     * @param PlaceholderResolverFactoryInterface $placeholderResolverFactory
     */
    public function setPlaceholderResolverFactory(PlaceholderResolverFactoryInterface $placeholderResolverFactory)
    {
        $this->placeholderResolverFactory = $placeholderResolverFactory;
    }

    /**
     * Called to reconfigure the specified Configuration Builder to be similar to this instance
     *
     * @param AbstractConfigurationBuilder $configurationBuilder
     */
    public function reconfigure(AbstractConfigurationBuilder $configurationBuilder)
    {
        if (null !== $this->placeholderResolverFactory) {
            $configurationBuilder->setPlaceholderResolverFactory($this->placeholderResolverFactory);
        }

        $configurationBuilder->setConfigurationFactory($this->configurationFactory());
    }

    /**
     * Internal build
     *
     * @param ConfigurationInterface $configuration
     */
    abstract protected function internalBuild(ConfigurationInterface $configuration);
}
