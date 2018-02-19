<?php

namespace App\Controller\Admin\Content;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\Content\Page;
use App\Repository\Content\PageRepository;
use App\Form\Admin\Content\PageType as PageFormType;
use App\Service\Serializer;

/**
 * Class PageController
 * todo
 */
class PageController extends AbstractController {
	
	/**
	 * List of Product Categories.
	 * @param Serializer          $serializer     Service to convert entities into json.
	 * @param PageRepository      $pageRepository Repository service of categories.
	 * @return array
	 *
	 * RouteName: app_admin_content_page_list
	 * @Route("/admin/page/list")
	 * @Template
	 */
	public function listAction(Serializer $serializer, PageRepository $pageRepository) {
		$pageRows = $pageRepository->findAll();
		
		return [
			'listAsJson' => $serializer->toJson($pageRows, ['attributes' => ['id', 'code', 'title', 'description']]),
		];
	}
	
	/**
	 * Update a Page.
	 * @param string              $code
	 * @param TranslatorInterface $translator
	 * @param PageRepository      $pageRepository
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_content_page_update
	 * @Route("/admin/page/update/{code}")
	 * @Template("admin/content/page/form.html.twig")
	 */
	public function updateAction($code, TranslatorInterface $translator, PageRepository $pageRepository) {
		/** @var Page $page */
		$page = $pageRepository->findOneBy(['code' => $code]);
		
		return $this->form($page, $translator);
	}
	
	/**
	 * Generate form view to Product Page.
	 * @param Page                $page
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 */
	protected function form(Page $page, TranslatorInterface $translator) {
		// Create form.
		$form = $this->createForm(PageFormType::class, $page);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($page);
			$this->getDm()->flush();
			
			$this->addFlash('success', $translator->trans('message.success.saved'));
			
			return $this->redirectToRoute('app_admin_content_page_list');
		}
		
		// Form view.
		return [
			"page"     => $page,
			"formView" => $form->createView(),
		];
	}
	
}