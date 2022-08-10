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

use Dflydev\DotAccessData\Exception\MissingPathException;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testGet(): void
    {
        $configuration = new Configuration($this->getTestData());

        $this->runBasicTests($configuration);
    }

    protected function getTestData(): array
    {
        return [
            'a' => [
                'b' => [
                    'c' => 'ABC',
                ],
            ],
            'abc' => '%a.b.c%',
            'some' => [
                'object' => new ConfigurationTestObject('some.object'),
                'other' => [
                    'object' => new ConfigurationTestObject('some.other.object'),
                ],
            ],
            'object' => new ConfigurationTestObject('object'),
            'an_array' => ['hello'],
        ];
    }

    protected function runBasicTests(AbstractConfiguration $configuration): void
    {
        $this->assertEquals('ABC', $configuration->get('a.b.c'), 'Direct access by dot notation');
        $this->assertEquals('ABC', $configuration->get('abc'), 'Resolved access');
        $this->assertEquals('object', $configuration->get('object')->key);
        $this->assertEquals('some.object', $configuration->get('some.object')->key);
        $this->assertEquals('some.other.object', $configuration->get('some.other.object')->key);
        $this->assertEquals(['hello'], $configuration->get('an_array'));
        $this->assertEquals('This is ABC', $configuration->resolve('This is %a.b.c%'));
        $this->assertNull($configuration->resolve());
    }

    public function testUnresolvedAccess(): void
    {
        $configuration = new Configuration($this->getTestData());

        $configuration->append('abcd', '%a.b.c.d%');

        $this->expectException(MissingPathException::class);
        $this->assertEquals('%a.b.c.d%', $configuration->get('abcd'), 'Unresolved access');
    }

    public function testAppend(): void
    {
        $configuration = new Configuration($this->getTestData());

        $configuration->append('a.b.c', 'abc');
        $configuration->append('an_array', 'world');

        $this->assertEquals(['ABC', 'abc'], $configuration->get('a.b.c'));
        $this->assertEquals(['hello', 'world'], $configuration->get('an_array'));
    }

    public function testExportRaw(): void
    {
        $configuration = new Configuration($this->getTestData());

        // Start with "known" expected value.
        $expected = $this->getTestData();

        $this->assertEquals($expected, $configuration->exportRaw());

        // Simulate change on an object to ensure that objects
        // are being handled correctly.
        $expected['object']->key = 'object (modified)';

        // Make the same change in the object that the
        // configuration is managing.
        $configuration->get('object')->key = 'object (modified)';

        $this->assertEquals($expected, $configuration->exportRaw());
    }

    public function testExport(): void
    {
        $configuration = new Configuration($this->getTestData());

        // Start with "known" expected value.
        $expected = $this->getTestData();

        // We have one replacement that is expected to happen.
        // It should be represented in the export as the
        // resolved value!
        $expected['abc'] = 'ABC';

        $this->assertEquals($expected, $configuration->export());

        // Simulate change on an object to ensure that objects
        // are being handled correctly.
        $expected['object']->key = 'object (modified)';

        // Make the same change in the object that the
        // configuration is managing.
        $configuration->get('object')->key = 'object (modified)';

        $this->assertEquals($expected, $configuration->export());

        // Test to make sure that set will result in setting
        // a new value and also that export will show this new
        // value. (tests "export is dirty" functionality)
        $configuration->set('abc', 'ABCD');
        $expected['abc'] = 'ABCD';
        $this->assertEquals($expected, $configuration->export());
    }

    public function testExportData(): void
    {
        $configuration = new Configuration($this->getTestData());

        $data = $configuration->exportData();

        // The exportData call should return data filled with
        // resolved data.
        $this->assertEquals('ABC', $data->get('abc'));
    }

    public function testImportRaw(): void
    {
        $configuration = new Configuration();

        $configuration->importRaw($this->getTestData());

        $this->runBasicTests($configuration);
    }

    public function testImport(): void
    {
        $configuration = new Configuration();

        $configuration->import(new Configuration($this->getTestData()));

        $this->runBasicTests($configuration);
    }

    public function testSetPlaceholderResolver(): void
    {
        $placeholderResolver = $this->createMock('Dflydev\PlaceholderResolver\PlaceholderResolverInterface');

        $placeholderResolver
            ->expects($this->once())
            ->method('resolvePlaceholder')
            ->with($this->equalTo('foo'))
            ->willReturn('bar');

        $configuration = new Configuration();

        $configuration->setPlaceholderResolver($placeholderResolver);

        $this->assertEquals('bar', $configuration->resolve('foo'));
    }
}

class ConfigurationTestObject
{
    public $key;

    public function __construct($key)
    {
        $this->key = $key;
    }
}
