<?php

namespace App\Controller\Admin\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Controller\AbstractEggShopController;
use App\Entity\SimpleShop\Category;
use App\Form\Admin\SimpleShop\CategoryType as CategoryFormType;
use App\Service\Serializer;

/**
 * Class CategoryController
 */
class CategoryController extends AbstractEggShopController {
	
	/**
	 * List of Product Categories.
	 *
	 * RouteName: app_admin_simpleshop_category_list
	 * @Route("/admin/category/list")
	 * @Template
	 *
	 * @param Serializer         $serializer         Service to convert entities into json.
	 * @return array
	 */
	public function listAction(Serializer $serializer) {
		$categoryRows = $this->getSimpleShopCategoryRepository()->findAllWithProducts();
		
		return [
			'listAsJson' => $serializer->toJson($categoryRows, ['attributes' => [
				'id', 'label', 'active', 'products' => ['label'],
			]]),
		];
	}
	
	/**
	 * Create a Product Category.
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_simpleshop_category_create
	 * @Route("/admin/category/create")
	 * @Template("admin/simple_shop/category/form.html.twig")
	 */
	public function createAction(TranslatorInterface $translator) {
		return $this->form(new Category(), $translator);
	}
	
	/**
	 * Update a Product Category.
	 * @param Category $category
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_category_update
	 * @Route("/admin/category/update/{category}", requirements={"category"="\d+|_id_"})
	 * @Template("admin/simple_shop/category/form.html.twig")
	 */
	public function updateAction(Category $category, TranslatorInterface $translator) {
		return $this->form($category, $translator);
	}
	
	/**
	 * Generate form view to Product Category.
	 * @param Category $category
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 */
	protected function form(Category $category, TranslatorInterface $translator) {
		// Create form.
		$form = $this->createForm(CategoryFormType::class, $category);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($category);
			$this->getDm()->flush();
			
			$this->addFlash('success', $translator->trans('message.success.saved'));
			
			return $this->redirectToRoute('app_admin_simpleshop_category_list');
		}
		
		// Form view.
		return [
			"category" => $category,
			"formView"       => $form->createView(),
		];
	}
	
}