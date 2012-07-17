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

use Symfony\Component\Yaml\Yaml;

class YamlConfigurationBuilder extends AbstractConfigurationBuilder
{
    /**
     * YAML input string
     *
     * @var string
     */
    protected $input;

    /**
     * Constructor
     *
     * @param string|null $input
     */
    public function __construct($input = null)
    {
        $this->input = $input;
    }

    /**
     * {@inheritdocs}
     */
    public function internalBuild(ConfigurationInterface $configuration)
    {
        if (null !== $this->input) {
            $configuration->importRaw(Yaml::parse($this->input));
        }
    }
}
