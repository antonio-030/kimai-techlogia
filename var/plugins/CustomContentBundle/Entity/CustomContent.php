<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class CustomContent
{
    private ?string $stylesheet = null;
    private ?string $javascript = null;
    private ?string $alert = null;
    private ?string $news = null;
    #[Assert\Length(min: 0, max: 27)]
    private ?string $newsTitle = null;

    public function getStylesheet(): ?string
    {
        return $this->stylesheet;
    }

    public function setStylesheet(?string $customCss): void
    {
        $this->stylesheet = $customCss;
    }

    public function getJavascript(): ?string
    {
        return $this->javascript;
    }

    public function setJavascript(?string $javascript): void
    {
        $this->javascript = $javascript;
    }

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): void
    {
        $this->alert = $alert;
    }

    public function getNews(): ?string
    {
        return $this->news;
    }

    public function setNews(?string $news): void
    {
        $this->news = $news;
    }

    public function getNewsTitle(): ?string
    {
        return $this->newsTitle;
    }

    public function setNewsTitle(?string $newsTitle): void
    {
        $this->newsTitle = $newsTitle;
    }
}
