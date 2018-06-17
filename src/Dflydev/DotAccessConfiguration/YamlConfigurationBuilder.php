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

use Psr\Log\InvalidArgumentException;
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
            try{
                $yml = Yaml::parse($this->input, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
            } catch (\Exception $e) {
                throw new InvalidArgumentException($e->getMessage(), 0, $e);
            }
            if (is_string($yml))
            {
                throw(new \InvalidArgumentException('Yaml could not be parsed, parser detected a string.'));
            }
            $configuration->importRaw($yml);
        }
    }
}
