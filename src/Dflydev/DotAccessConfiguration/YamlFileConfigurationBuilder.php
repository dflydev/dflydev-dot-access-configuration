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

use Dflydev\DotAccessData\Util as DotAccessDataUtil;
use Symfony\Component\Yaml\Yaml;

class YamlFileConfigurationBuilder extends AbstractConfigurationBuilder
{
    /**
     * YAML Configuration Filenames
     *
     * @var array
     */
    private $yamlConfigurationFilenames;

    /**
     * Constructor
     *
     * @param array $yamlConfigurationFilenames
     */
    public function __construct(array $yamlConfigurationFilenames)
    {
        $this->yamlConfigurationFilenames = $yamlConfigurationFilenames;
    }

    /**
     * {@inheritdocs}
     */
    public function internalBuild(ConfigurationInterface $configuration)
    {
        $config = array();
        $imports = array();
        foreach ($this->yamlConfigurationFilenames as $yamlConfigurationFilename) {
            if (file_exists($yamlConfigurationFilename)) {
                $config = DotAccessDataUtil::mergeAssocArray($config, Yaml::parse($yamlConfigurationFilename));
                if (isset($config['imports'])) {
                    foreach ((array) $config['imports'] as $file) {
                        if (0 === strpos($file, '/')) {
                            // Absolute path
                            $imports[] = $file;
                        } else {
                            if ($realpath = realpath(dirname($yamlConfigurationFilename).'/'.$file)) {
                                $imports[] = $realpath;
                            }
                        }
                    }
                }
            }
        }

        if ($imports) {
            $importsBuilder = new static($imports);

            // We want to reconfigure the imports builder to have the
            // same basic configuration as this instance.
            $this->reconfigure($importsBuilder);

            $configuration->import($importsBuilder->build());

            $internalImports = $configuration->get('imports');
        } else {
            $internalImports = null;
        }

        $configuration->importRaw($config);

        if ($internalImports) {
           foreach ((array) $internalImports as $import) {
                $configuration->append('imports', $import);
            }
        }

        return $configuration;
    }
}
