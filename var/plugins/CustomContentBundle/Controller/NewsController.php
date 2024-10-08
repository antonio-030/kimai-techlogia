<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\Controller;

use App\Controller\AbstractController;
use App\Utils\PageSetup;
use KimaiPlugin\CustomContentBundle\Repository\CustomContentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
class NewsController extends AbstractController
{
    #[Route(path: '/custom-content-news', name: 'custom_content_render_news', methods: ['GET', 'POST'])]
    public function indexAction(CustomContentRepository $repository): Response
    {
        $content = $repository->getCustomContent();
        $title = $content->getNewsTitle();

        if ($title === null || \strlen($title) < 1) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('@CustomContent/news.html.twig', [
            'page_setup' => new PageSetup($title),
            'content' => $content->getNews(),
        ]);
    }
}
