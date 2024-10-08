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
use KimaiPlugin\CustomContentBundle\Entity\CustomContent;
use KimaiPlugin\CustomContentBundle\Form\CustomContentType;
use KimaiPlugin\CustomContentBundle\Repository\CustomContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/custom-content')]
#[IsGranted('edit_custom_content')]
class AdminController extends AbstractController
{
    #[Route(path: '', name: 'custom_content', methods: ['GET', 'POST'])]
    public function indexAction(): Response
    {
        if ($this->isGranted('css_custom_content')) {
            return $this->redirectToRoute('custom_content_css');
        } elseif ($this->isGranted('js_custom_content')) {
            return $this->redirectToRoute('custom_content_javascript');
        } elseif ($this->isGranted('alert_custom_content')) {
            return $this->redirectToRoute('custom_content_alert');
        } elseif ($this->isGranted('news_custom_content')) {
            return $this->redirectToRoute('custom_content_news');
        }

        throw $this->createAccessDeniedException('You cannot access any of the custom content screens');
    }

    #[Route(path: '/css', name: 'custom_content_css', methods: ['GET', 'POST'])]
    #[IsGranted('css_custom_content')]
    public function stylesheet(Request $request, CustomContentRepository $repository): Response
    {
        return $this->renderTab($request, $repository, 'css', 'custom_content_css');
    }

    #[Route(path: '/javascript', name: 'custom_content_javascript', methods: ['GET', 'POST'])]
    #[IsGranted('js_custom_content')]
    public function javascript(Request $request, CustomContentRepository $repository): Response
    {
        return $this->renderTab($request, $repository, 'javascript', 'custom_content_javascript');
    }

    #[Route(path: '/alert', name: 'custom_content_alert', methods: ['GET', 'POST'])]
    #[IsGranted('alert_custom_content')]
    public function alert(Request $request, CustomContentRepository $repository): Response
    {
        return $this->renderTab($request, $repository, 'alert', 'custom_content_alert');
    }

    #[Route(path: '/news', name: 'custom_content_news', methods: ['GET', 'POST'])]
    #[IsGranted('news_custom_content')]
    public function news(Request $request, CustomContentRepository $repository): Response
    {
        return $this->renderTab($request, $repository, 'news', 'custom_content_news');
    }

    private function renderTab(Request $request, CustomContentRepository $repository, string $type, string $route): Response
    {
        $entity = $repository->getCustomContent();

        $form = $this->createForm(CustomContentType::class, $entity, [
            'action' => $this->generateUrl($route),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CustomContent $entity */
            $entity = $form->getData();

            try {
                $repository->saveCustomContent($entity);
                $this->flashSuccess('action.update.success');

                return $this->redirectToRoute($route);
            } catch (\Exception $ex) {
                $this->flashUpdateException($ex);
            }
        }

        // automatically import rules from custom-css-bundle
        $import = $repository->getImportContent();
        if ($import !== null && \strlen(trim($import)) > 0) {
            $entity->setStylesheet($entity->getStylesheet() . PHP_EOL . $import);
            $repository->saveCustomContent($entity);
            $repository->removeImportContent();

            return $this->redirectToRoute($route);
        }

        $page = new PageSetup('Custom content');
        $page->setHelp('plugin-custom-content.html');

        return $this->render('@CustomContent/index.html.twig', [
            'page_setup' => $page,
            'form' => $form->createView(),
            'tab' => $type
        ]);
    }
}
