<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bit_bag_sylius_multi_cart_plugin');
        $rootNode = $treeBuilder->getRootNode();

//        $rootNode
//            ->children()
//                ->arrayNode('resources')

        return $treeBuilder;
    }
}
