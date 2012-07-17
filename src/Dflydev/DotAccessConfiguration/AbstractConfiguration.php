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
use Dflydev\PlaceholderResolver\RegexPlaceholderResolver;

abstract class AbstractConfiguration implements ConfigurationInterface
{
    private $placeholderResolver;
    private $data;
    private $exportIsDirty = true;
    private $resolvedExport;

    /**
     * {@inheritdocs}
     */
    public function getRaw($key)
    {
        return $this->data()->get($key);
    }

    /**
     * {@inheritdocs}
     */
    public function get($key)
    {
        $value = $this->getRaw($key);
        if (is_object($value)) {
            return $value;
        }
        $this->resolveValues($value);

        return $value;
    }

    /**
     * {@inheritdocs}
     */
    public function set($key, $value = null)
    {
        $this->exportIsDirty = true;

        return $this->data()->set($key, $value);
    }

    /**
     * {@inheritdocs}
     */
    public function append($key, $value = null)
    {
        $this->exportIsDirty = true;

        return $this->data()->append($key, $value);
    }

    /**
     * {@inheritdocs}
     */
    public function exportRaw()
    {
        return $this->data()->export();
    }

    /**
     * {@inheritdocs}
     */
    public function export()
    {
        if ($this->exportIsDirty) {
            $this->resolvedExport = $this->data()->export();
            $this->resolveValues($this->resolvedExport);
            $this->exportIsDirty = false;
        }

        return $this->resolvedExport;
    }

    /**
     * {@inheritdocs}
     */
    public function exportData()
    {
        return new Data($this->export());
    }

    /**
     * {@inheritdocs}
     */
    public function importRaw($imported = null, $clobber = true)
    {
        $this->exportIsDirty = true;

        if (null !== $imported) {
            $this->data()->import($imported, $clobber);
        }
    }

    /**
     * {@inheritdocs}
     */
    public function import(ConfigurationInterface $imported, $clobber = true)
    {
        return $this->importRaw($imported->exportRaw(), $clobber);
    }

    /**
     * {@inheritdocs}
     */
    public function resolve($value = null)
    {
        if (null === $value) {
            return null;
        }

        return $this->placeholderResolver()->resolvePlaceholder($value);
    }

    /**
     * {@inheritdocs}
     */
    public function setPlaceholderResolver(PlaceholderResolverInterface $placeholderResolver)
    {
        $this->placeholderResolver = $placeholderResolver;

        return $this;
    }

    /**
     * Resolve values
     *
     * For objects, do nothing. For strings, resolve placeholder.
     * For arrays, call resolveValues() on each item.
     *
     * @param mixed $input
     */
    protected function resolveValues(&$input = null)
    {
        if (is_array($input)) {
            foreach ($input as $idx => $value) {
                $this->resolveValues($value);
                $input[$idx] = $value;
            }
        } else {
            if (!is_object($input)) {
                $input = $this->placeholderResolver()->resolvePlaceholder($input);
            }
        }
    }

    /**
     * Data
     *
     * @return Data
     */
    protected function data()
    {
        if (null === $this->data) {
            $this->data = new Data;
        }

        return $this->data;
    }

    /**
     * Placeholder Resolver
     *
     * @return PlaceholderResolverInterface
     */
    protected function placeholderResolver()
    {
        if (null === $this->placeholderResolver) {
            $this->placeholderResolver = new RegexPlaceholderResolver(new ConfigurationDataSource($this), '%', '%');
        }

        return $this->placeholderResolver;
    }
}
