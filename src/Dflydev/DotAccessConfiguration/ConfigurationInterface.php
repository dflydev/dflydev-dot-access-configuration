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

use Dflydev\DotAccessData\Data;
use Dflydev\PlaceholderResolver\PlaceholderResolverInterface;

interface ConfigurationInterface
{
    /**
     * Get a value (with placeholders unresolved)
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function getRaw($key);

    /**
     * Get a value (with placeholders resolved)
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function get($key);

    /**
     * Set a value
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value = null);

    /**
     * Append a value
     * 
     * Will force key to be an array if it is only a string
     * 
     * @param string $key
     * @param mixed $value
     */
    public function append($key, $value = null);

    /**
     * Export configuration data as an associtaive array (with placeholders unresolved)
     * 
     * @return array
     */    
    public function exportRaw();

    /**
     * Export configuration data as an associtaive array (with placeholders resolved)
     * 
     * @return array
     */    
    public function export();

    /**
     * Underlying Data representation
     * 
     * Will have all placeholders resolved.
     * 
     * @return Data
     */
    public function exportData();

    /**
     * Import another Configuration
     * 
     * @param array $imported
     * @param bool $clobber
     */    
    public function importRaw($imported, $clobber = true);

    /**
     * Import another Configuration
     * 
     * @param ConfigurationInterface $imported
     * @param bool $clobber
     */    
    public function import(ConfigurationInterface $imported, $clobber = true);

    /**
     * Resolve placeholders in value from configuration
     * 
     * @param string|null $value
     * 
     * @return string
     */
    public function resolve($value = null);

    /**
     * Set Placeholder Resolver
     * 
     * @param PlaceholderResolver $placeholderResolver
     * 
     * @return ConfigurationInterface
     */
    public function setPlaceholderResolver(PlaceholderResolverInterface $placeholderResolver);
}
