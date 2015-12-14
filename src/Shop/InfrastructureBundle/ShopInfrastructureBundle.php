<?php

namespace Shop\InfrastructureBundle;

use SimpleBus\SymfonyBridge\DependencyInjection\Compiler\ConfigureMiddlewares;
use SimpleBus\SymfonyBridge\DependencyInjection\Compiler\RegisterHandlers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShopInfrastructureBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new ConfigureMiddlewares(
                'query_bus',
                'query_bus_middleware'
            )
        );

        $container->addCompilerPass(
            new RegisterHandlers(
                'simple_bus.query_bus.query_handler_map',
                'query_handler',
                'handles'
            )
        );
    }
}
