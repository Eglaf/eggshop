<?php

namespace App\Controller\Admin\Content;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\Content\Text;
use App\Repository\Content\TextRepository;
use App\Form\Admin\Content\TextType as TextFormType;
use App\Service\Serializer;

/**
 * Class TextController
 * todo
 */
class TextController extends AbstractController {
	
	/**
	 * List of Product Categories.
	 * @param Serializer     $serializer     Service to convert entities into json.
	 * @param TextRepository $textRepository Repository service of categories.
	 * @return array
	 *
	 * RouteName: app_admin_content_text_list
	 * @Route("/admin/text/list")
	 * @Template
	 */
	public function listAction(Serializer $serializer, TextRepository $textRepository) {
		$textRows = $textRepository->findAll();
		
		return [
			'listAsJson' => $serializer->toJson($textRows, ['attributes' => ['id', 'code', 'title']]),
		];
	}
	
	/**
	 * Update a Product Text.
	 * @param string         $code
	 * @param TranslatorInterface $translator
	 * @param TextRepository $textRepository
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_content_text_update
	 * @Route("/admin/text/update/{code}")
	 * @Template("admin/content/text/form.html.twig")
	 */
	public function updateAction($code, TranslatorInterface $translator, TextRepository $textRepository) {
		/** @var Text $text */
		$text = $textRepository->findOneBy(['code' => $code]);
		
		return $this->form($text, $translator);
	}
	
	/**
	 * Generate form view to Product Text.
	 * @param Text $text
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 */
	protected function form(Text $text, TranslatorInterface $translator) {
		// Create form.
		$form = $this->createForm(TextFormType::class, $text);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($text);
			$this->getDm()->flush();
			
			$this->addFlash('success', $translator->trans('message.success.saved'));
			
			return $this->redirectToRoute('app_admin_content_text_list');
		}
		
		// Form view.
		return [
			"text"     => $text,
			"formView" => $form->createView(),
		];
	}
	
}