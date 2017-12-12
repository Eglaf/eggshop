<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\SimpleShop\Category as CategoryEntity;
use App\Form\Admin\SimpleShop\CategoryType as CategoryFormType;
use App\Service\Serializer;

/**
 * Class CategoryController
 */
class CategoryController extends AbstractController {
	
	/**
	 * List of Product Categories.
	 *
	 * RouteName: app_admin_category_list
	 * @Route("/admin/category/list")
	 * @Template
	 *
	 * @param Serializer $serializer Service to convert entities into json.
	 * @return array
	 */
	public function listAction(Serializer $serializer) {
		$rows = $this->getDm()->getRepository(CategoryEntity::class)->findAll();
		
		return [
			'rowsAsJson' => $serializer->toJson($rows),
		];
	}
	
	/**
	 * Create a Product Category.
	 *
	 * RouteName: app_admin_category_create
	 * @Route("/admin/category/create")
	 * @Template("admin/category/form.html.twig")
	 *
	 * @return array|RedirectResponse
	 */
	public function createAction() {
		return $this->form(new CategoryEntity());
	}
	
	/**
	 * Update a Product Category.
	 *
	 * RouteName: app_admin_category_update
	 * @Route("/admin/category/update/{enCategory}", requirements={"enCategory"="\d+"})
	 * @Template("admin/category/form.html.twig")
	 *
	 * @param CategoryEntity $enCategory
	 * @return array|RedirectResponse
	 */
	public function updateAction(CategoryEntity $enCategory) {
		return $this->form($enCategory);
	}
	
	/**
	 * Generate form view to Product Category.
	 * @param CategoryEntity $enCategory
	 * @return array|RedirectResponse
	 */
	protected function form(CategoryEntity $enCategory) {
		// Create form.
		$oForm = $this->createForm(CategoryFormType::class, $enCategory);
		$oForm->handleRequest($this->getRq());
		
		// Save form.
		if ($oForm->isSubmitted() && $oForm->isValid()) {
			$this->getDm()->persist($enCategory);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_admin_category_list');
		}
		
		// Form view.
		return [
			"enCategory" => $enCategory,
			"oForm"      => $oForm->createView(),
		];
	}
	
}