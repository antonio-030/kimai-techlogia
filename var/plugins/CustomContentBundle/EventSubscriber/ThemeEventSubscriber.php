<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\EventSubscriber;

use App\Event\ThemeEvent;
use App\Utils\Markdown;
use KimaiPlugin\CustomContentBundle\Entity\CustomContent;
use KimaiPlugin\CustomContentBundle\Repository\CustomContentRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ThemeEventSubscriber implements EventSubscriberInterface
{
    private ?CustomContent $content = null;

    public function __construct(private CustomContentRepository $repository, private Markdown $markdown)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ThemeEvent::STYLESHEET => ['renderStylesheet', 100],
            ThemeEvent::JAVASCRIPT => ['renderJavascript', 100],
            ThemeEvent::CONTENT_START => ['renderAlert', 100],
        ];
    }

    private function getContent(): CustomContent
    {
        if ($this->content === null) {
            $this->content = $this->repository->getCustomContent();
        }

        return $this->content;
    }

    public function renderStylesheet(ThemeEvent $event): void
    {
        $css = $this->getContent()->getStylesheet();
        if (empty($css)) {
            return;
        }

        // these rules make sure that injected HTML will not be interpreted by the browser
        $css = str_replace(['</', '<s'], ['&lt;/', '&lt;s'], $css);

        // the others are shrink the output size
        $css = str_replace([PHP_EOL, "\n", "\r"], [' ', ' ', ' '], $css);
        $css = '<style>' . trim($css) . '</style>';

        $event->addContent($css);
    }

    public function renderJavascript(ThemeEvent $event): void
    {
        $javascript = $this->getContent()->getJavascript();
        if (empty($javascript)) {
            return;
        }

        // these rules format/shrink the output to reduce the overall size
        $javascript = str_replace([PHP_EOL, "\n", "\r"], [' ', ' ', ' '], $javascript);
        $javascript = '<script>' . trim($javascript) . '</script>';

        $event->addContent($javascript);
    }

    public function renderAlert(ThemeEvent $event): void
    {
        $css = $this->getContent()->getAlert();
        if (empty($css)) {
            return;
        }

        $html = '<div class="alert alert-warning alert-important" role="alert">' . $this->markdown->toHtml($css) . '</div>';

        $event->addContent($html);
    }
}
