<?php
namespace App\TurboLabIt\ServiceEntityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TurboLabItServiceEntityExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');


        $this->addAnnotatedClassesToCompile([
            '**Bundle\\src\\',
        ]);
    }
}
