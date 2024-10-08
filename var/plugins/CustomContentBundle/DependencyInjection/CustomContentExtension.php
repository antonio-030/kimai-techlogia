<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CustomContentExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        /*
         * @CloudRequired adapt if new permissions are added
         */
        $container->prependExtensionConfig('kimai', [
            'permissions' => [
                'roles' => [
                    'ROLE_SUPER_ADMIN' => [
                        'edit_custom_content',
                        'js_custom_content',
                        'css_custom_content',
                        'alert_custom_content',
                        'news_custom_content',
                    ],
                ],
            ],
        ]);
    }
}
