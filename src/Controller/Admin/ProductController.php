<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\SimpleShop\Product;
use App\Repository\SimpleShop\ProductRepository as ProductRepository;
use App\Form\Admin\SimpleShop\ProductType as ProductFormType;
use App\Service\Serializer;

/**
 * Class ProductController
 */
class ProductController extends AbstractController {
	
	/**
	 * List of Product Categories.
	 *
	 * RouteName: app_admin_product_list
	 * @Route("/admin/product/list")
	 * @Template
	 *
	 * @param Serializer         $serializer         Service to convert entities into json.
	 * @param ProductRepository $productRepository Repository service of categories.
	 * @return array
	 */
	public function listAction(Serializer $serializer, ProductRepository $productRepository) {
		$productRows = $productRepository->findAllWithCategory();
		
		return [
			'listAsJson' => $serializer->toJson($productRows, ['attributes' => ['id', 'label', 'active', 'category' => ['label']]]),
		];
	}
	
	/**
	 * Create a Product Product.
	 *
	 * RouteName: app_admin_product_create
	 * @Route("/admin/product/create")
	 * @Template("admin/product/form.html.twig")
	 *
	 * @return array|RedirectResponse
	 */
	public function createAction() {
		return $this->form(new Product());
	}
	
	/**
	 * Update a Product Product.
	 *
	 * RouteName: app_admin_product_update
	 * @Route("/admin/product/update/{product}", requirements={"product"="\d+|_id_"})
	 * @Template("admin/product/form.html.twig")
	 *
	 * @param Product $product
	 * @return array|RedirectResponse
	 */
	public function updateAction(Product $product) {
		return $this->form($product);
	}
	
	/**
	 * Generate form view to Product Product.
	 * @param Product $product
	 * @return array|RedirectResponse
	 */
	protected function form(Product $product) {
		// Create form.
		$form = $this->createForm(ProductFormType::class, $product);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($product);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_admin_product_list');
		}
		
		// Form view.
		return [
			"product" => $product,
			"formView"       => $form->createView(),
		];
	}
	
}