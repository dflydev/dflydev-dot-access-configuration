Dot Access Configuration
========================

Given a deep data structure representing a configuration, access
configuration by dot notation.

This library combines [dflydev/dot-access-data](https://github.com/dflydev/dflydev-dot-access-data)
and [dflydev/placeholder-resolver](https://github.com/dflydev/dflydev-placeholder-resolver)
to provide a complete configuration solution.


Requirements
------------

 * PHP (5.3+)
 * [dflydev/dot-access-data](https://github.com/dflydev/dflydev-dot-access-data) (1.*)
 * [dflydev/placeholder-resolver](https://github.com/dflydev/dflydev-placeholder-resolver) (1.*)
 * [symfony/yaml](https://github.com/symfony/Yaml) (>2,<2.2) *(suggested)*


Usage
-----

Generally one will use an implementation of `ConfigurationBuilderInterface`
to build `ConfigurationInterface` instances. For example, to build a Configuration
out of a YAML file, one would use the `YamlFileConfigurationBuilder`:

    use Dflydev\DotAccessConfiguration\YamlFileConfigurationBuilder;
    
    $configurationBuilder = new YamlFileConfigurationBuilder('config/config.yml');
    $configuration = $configurationBuilder->build();


Once created, the Configuration instance behaves similarly to a Data
instance from [dflydev/dot-access-data](https://github.com/dflydev/dflydev-dot-access-data).

    $configuration->set('a.b.c', 'ABC');
    $configuration->get('a.b.c');
    $configuration->set('a.b.e', array('A', 'B', 'C'));
    $configuration->append('a.b.e', 'D');


Custom Configurations
---------------------

Configuration Builders use Configuration Factories and Placeholder Resolver
Factories behind the scenes in order to build a working configuration.

Under normal circumstances one should not need to do anything with the
Placeholder Resolver Factory. However, one may wish to extend the
default `Configuration` class or use an entirely different implementation
altogether.

In order to build instances of custom `ConfigurationInterface` implementations
with the standard builders, one would need to implement
`ConfigurationFactoryInterface` and inject it into any
`ConfigurationBuilderInterface`.

If a Configuration is declared as follows:

    namespace MyProject;
    
    use Dflydev\DotAccessConfiguration\Configuration;
    
    class MyConf extends Configuration
    {
        public function someSpecialMethod()
        {
            // Whatever you want here.
        }
    }

Create the following factory:

    namespace MyProject;
    
    use Dflydev\DotAccessConfiguration\ConfigurationFactoryInterface;
    
    class MyConfFactory implements ConfigurationFactoryInterface
    {
        /**
         * {@inheritdocs}
         */
        public function create()
        {
            return new MyConf;
        }
    }

To use the factory with any builder, inject it as follows:

    use Dflydev\DotAccessConfiguration\YamlFileConfigurationBuilder;
    use MyProject\MyConfFactory;
    
    $configurationBuilder = new YamlFileConfigurationBuilder('config/config.yml');
    
    // Inject your custom Configuration Factory
    $configurationBuilder->setConfigurationFactory(new MyConfFactory);

    // Will now build instances of MyConfFactory instead of
    // the standard Configuration implementation.
    $configuration = $configurationBuilder->build();


License
-------

This library is licensed under the New BSD License - see the LICENSE file
for details.


Community
---------

If you have questions or want to help out, join us in the
[#dflydev](irc://irc.freenode.net/#dflydev) channel on irc.freenode.net.