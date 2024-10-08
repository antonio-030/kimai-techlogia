<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\Repository;

use App\Configuration\ConfigurationService;
use App\Configuration\SystemConfiguration;
use App\Entity\Configuration;
use App\Utils\FileHelper;
use Exception;
use KimaiPlugin\CustomContentBundle\Entity\CustomContent;

class CustomContentRepository
{
    public const TYPE_ALERT = 'alert.md';
    public const TYPE_NEWS = 'news.md';
    public const TYPE_STYLESHEET = 'styles.css';
    public const TYPE_JAVASCRIPT = 'script.js';

    public const CONFIG_KEY = 'custom_content.news_title';

    private const ALL = [self::TYPE_ALERT, self::TYPE_NEWS, self::TYPE_JAVASCRIPT, self::TYPE_STYLESHEET];

    public function __construct(private FileHelper $fileHelper, private SystemConfiguration $systemConfiguration, private ConfigurationService $repository)
    {
    }

    public function getImportContent(): ?string
    {
        $file = $this->fileHelper->getDataDirectory() . '/custom-css-bundle.css';

        if (file_exists($file)) {
            $content = file_get_contents($file);
            if ($content === false) {
                return null;
            }

            return $content;
        }

        return null;
    }

    public function removeImportContent(): void
    {
        $file = $this->fileHelper->getDataDirectory() . '/custom-css-bundle.css';

        if (file_exists($file)) {
            @unlink($file);
        }
    }

    private function getStorageFilename(string $type): string
    {
        return $this->fileHelper->getDataDirectory('custom-content-bundle') . $type;
    }

    /**
     * @param CustomContent $content
     * @throws Exception
     */
    public function saveCustomContent(CustomContent $content): void
    {
        $this->save(self::TYPE_STYLESHEET, $content->getStylesheet());
        $this->save(self::TYPE_ALERT, $content->getAlert());
        $this->save(self::TYPE_JAVASCRIPT, $content->getJavascript());
        $this->save(self::TYPE_NEWS, $content->getNews());

        $config = $this->repository->getConfiguration(self::CONFIG_KEY);
        if ($config === null) {
            $config = new Configuration();
            $config->setName(self::CONFIG_KEY);
        }
        $config->setValue($content->getNewsTitle());

        $this->repository->saveConfiguration($config);
    }

    /**
     * @param string $type
     * @param string|null $content
     * @throws Exception
     */
    private function save(string $type, ?string $content): void
    {
        if (!\in_array($type, self::ALL)) {
            throw new Exception('Unknown type given: ' . $type);
        }

        $file = $this->getStorageFilename($type);

        if (file_exists($file) && !is_writable($file)) {
            throw new Exception('Custom content file is not writable: ' . $file);
        }

        if (false === file_put_contents($file, $content)) {
            throw new Exception('Failed saving custom content rules to file: ' . $file);
        }
    }

    public function getNewsTitle(): ?string
    {
        $title = $this->systemConfiguration->find(self::CONFIG_KEY);

        if (\is_string($title) && \strlen($title) > 0) {
            return $title;
        }

        return null;
    }

    /**
     * @return CustomContent
     * @throws Exception
     */
    public function getCustomContent(): CustomContent
    {
        $entity = new CustomContent();

        try {
            $entity->setAlert($this->get(self::TYPE_ALERT));
            $entity->setJavascript($this->get(self::TYPE_JAVASCRIPT));
            $entity->setStylesheet($this->get(self::TYPE_STYLESHEET));

            $newsTitle = $this->getNewsTitle();
            if ($newsTitle !== null) {
                $entity->setNewsTitle($newsTitle);
            }
            $entity->setNews($this->get(self::TYPE_NEWS));
        } catch (Exception $e) {
            throw $e;
        }

        return $entity;
    }

    /**
     * @param string $type
     * @return string|null
     * @throws Exception
     */
    public function get(string $type): ?string
    {
        if (!\in_array($type, self::ALL)) {
            throw new Exception('Unknown type given: ' . $type);
        }

        $file = $this->getStorageFilename($type);

        if (file_exists($file) && is_readable($file)) {
            $content = file_get_contents($file);
            if ($content === false) {
                return null;
            }

            return $content;
        }

        return null;
    }
}
