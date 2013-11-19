<?php

namespace Webit\Bundle\PriceComparatorCeneoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webit_price_comparator_ceneo')
        ->children()
            ->scalarNode('category_source_file')->defaultValue('http://api.ceneo.pl/Kategorie/dane.xml')->end()
            ->scalarNode('offer_file_target_dir')->defaultValue('%kernel.root_dir%/Resources/ceneo')->end()
            ->scalarNode('offer_file_generation_interval')->defaultValue(1)->end()
        ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
