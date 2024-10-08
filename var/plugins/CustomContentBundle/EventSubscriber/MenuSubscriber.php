<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\EventSubscriber;

use App\Configuration\SystemConfiguration;
use App\Event\ConfigureMainMenuEvent;
use App\Utils\MenuItemModel;
use KimaiPlugin\CustomContentBundle\Repository\CustomContentRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuSubscriber implements EventSubscriberInterface
{
    private $security;
    private $configuration;

    public function __construct(AuthorizationCheckerInterface $security, SystemConfiguration $configuration)
    {
        $this->security = $security;
        $this->configuration = $configuration;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureMainMenuEvent::class => ['onMenuConfigure', 100],
        ];
    }

    public function onMenuConfigure(ConfigureMainMenuEvent $event): void
    {
        $auth = $this->security;

        if (!$auth->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return;
        }

        /** @var string|null $title */
        $title = $this->configuration->find(CustomContentRepository::CONFIG_KEY);
        if (null !== $title) {
            $menu = $event->getMenu();
            $model = new MenuItemModel('custom_content_render_news', $title, 'custom_content_render_news', [], 'far fa-newspaper');
            $menu->addChild($model);
        }

        $menu = $event->getSystemMenu();

        if ($auth->isGranted('edit_custom_content')) {
            $model = new MenuItemModel('custom_content', 'Custom content', 'custom_content', [], 'fas fa-pencil-alt');
            $model->setChildRoutes(['custom_content_css', 'custom_content_javascript', 'custom_content_alert', 'custom_content_news']);
            $menu->addChild($model);
        }
    }
}
