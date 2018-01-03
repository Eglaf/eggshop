<?php

namespace App\Controller\Admin\Content;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\Content\Text;
use App\Repository\Content\TextRepository;
use App\Form\Admin\Content\TextType as TextFormType;
use App\Service\Serializer;

/**
 * Class TextController
 */
class TextController extends AbstractController {
	
	/**
	 * List of Product Categories.
	 *
	 * RouteName: app_admin_content_text_list
	 * @Route("/admin/text/list")
	 * @Template
	 *
	 * @param Serializer     $serializer     Service to convert entities into json.
	 * @param TextRepository $textRepository Repository service of categories.
	 * @return array
	 */
	public function listAction(Serializer $serializer, TextRepository $textRepository) {
		$textRows = $textRepository->findAll();
		
		return [
			'listAsJson' => $serializer->toJson($textRows),
		];
	}
	
	/**
	 * Update a Product Text.
	 *
	 * RouteName: app_admin_text_update
	 * @Route("/admin/text/update/{code}")
	 * @Template("admin/content/text/form.html.twig")
	 *
	 * @param string         $code
	 * @param TextRepository $textRepository
	 * @return array|RedirectResponse
	 */
	public function updateAction($code, TextRepository $textRepository) {
		/** @var Text $text */
		$text = $textRepository->findOneBy(['code' => $code]);
		
		return $this->form($text);
	}
	
	/**
	 * Generate form view to Product Text.
	 * @param Text $text
	 * @return array|RedirectResponse
	 */
	protected function form(Text $text) {
		// Create form.
		$form = $this->createForm(TextFormType::class, $text);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($text);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_admin_content_text_list');
		}
		
		// Form view.
		return [
			"text"     => $text,
			"formView" => $form->createView(),
		];
	}
	
}