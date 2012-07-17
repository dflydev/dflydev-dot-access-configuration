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

class Configuration extends AbstractConfiguration
{
    /**
     * Constructor
     *
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->importRaw($config);
    }
}
