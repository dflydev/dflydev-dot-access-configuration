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

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    protected function getTestData()
    {
        return array(
            'a' => array(
                'b' => array(
                    'c' => 'ABC',
                ),
            ),
            'abc' => '%a.b.c%',
            'abcd' => '%a.b.c.d%',
            'some' => array(
                'object' => new ConfigurationTestObject('some.object'),
                'other' => array(
                    'object' => new ConfigurationTestObject('some.other.object'),
                ),
            ),
            'object' => new ConfigurationTestObject('object'),
            'an_array' => array('hello'),
        );
    }

    protected function runBasicTests($configuration)
    {
        $this->assertEquals('ABC', $configuration->get('a.b.c'), 'Direct access by dot notation');
        $this->assertEquals('ABC', $configuration->get('abc'), 'Resolved access');
        $this->assertEquals('%a.b.c.d%', $configuration->get('abcd'), 'Unresolved access');
        $this->assertEquals('object', $configuration->get('object')->key);
        $this->assertEquals('some.object', $configuration->get('some.object')->key);
        $this->assertEquals('some.other.object', $configuration->get('some.other.object')->key);
        $this->assertEquals(array('hello'), $configuration->get('an_array'));
        $this->assertEquals('This is ABC', $configuration->resolve('This is %a.b.c%'));
        $this->assertNull($configuration->resolve());
    }

    public function testGet()
    {
        $configuration = new Configuration($this->getTestData());

        $this->runBasicTests($configuration);
    }

    public function testAppend()
    {
        $configuration = new Configuration($this->getTestData());

        $configuration->append('a.b.c', 'abc');
        $configuration->append('an_array', 'world');

        $this->assertEquals(array('ABC', 'abc'), $configuration->get('a.b.c'));
        $this->assertEquals(array('hello', 'world'), $configuration->get('an_array'));
    }

    public function testExportRaw()
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

    public function testExport()
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

    public function testExportData()
    {
        $configuration = new Configuration($this->getTestData());

        $data = $configuration->exportData();

        // The exportData call should return data filled with
        // resolved data.
        $this->assertEquals('ABC', $data->get('abc'));
    }

    public function testImportRaw()
    {
        $configuration = new Configuration();

        $configuration->importRaw($this->getTestData());

        $this->runBasicTests($configuration);
    }

    public function testImport()
    {
        $configuration = new Configuration();

        $configuration->import(new Configuration($this->getTestData()));

        $this->runBasicTests($configuration);
    }

    public function testSetPlaceholderResolver()
    {
        $placeholderResolver = $this->getMock('Dflydev\PlaceholderResolver\PlaceholderResolverInterface');

        $placeholderResolver
            ->expects($this->once())
            ->method('resolvePlaceholder')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('bar'))
        ;

        $configuration = new Configuration;

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
