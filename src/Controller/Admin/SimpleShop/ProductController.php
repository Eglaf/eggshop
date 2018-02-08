<?php

namespace App\Controller\Admin\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Util;
use App\Controller\AbstractEggShopController;
use App\Entity\SimpleShop\Product;
use App\Entity\Content\File;
use App\Repository\SimpleShop\ProductRepository as ProductRepository;
use App\Form\Admin\SimpleShop\ProductType as ProductFormType;
use App\Service\Serializer;

/**
 * Class ProductController
 */
class ProductController extends AbstractEggShopController {
	
	/**
	 * List of Product Categories.
	 * @param Serializer        $serializer        Service to convert entities into json.
	 * @param ProductRepository $productRepository Repository service of categories.
	 * @return array
	 *
	 * RouteName: app_admin_simpleshop_product_list
	 * @Route("/admin/product/list")
	 * @Template
	 */
	public function listAction(Serializer $serializer, ProductRepository $productRepository) {
		$productRows = $productRepository->findAllWithCategory();
		
		return [
			'listAsJson' => $serializer->toJson($productRows, ['attributes' => ['id', 'label', 'active', 'price', 'category' => ['label']]]),
		];
	}
	
	/**
	 * Create a Product.
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_simpleshop_product_create
	 * @Route("/admin/product/create")
	 * @Template("admin/simple_shop/product/form.html.twig")
	 */
	public function createAction() {
		return $this->form(new Product());
	}
	
	/**
	 * Update a Product.
	 * @param Product $product
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_simpleshop_product_update
	 * @Route("/admin/product/update/{product}", requirements={"product"="\d+|_id_"})
	 * @Template("admin/simple_shop/product/form.html.twig")
	 */
	public function updateAction(Product $product) {
		return $this->form($product);
	}
	
	/**
	 * Generate form view to Product.
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
			
			return $this->redirectToRoute('app_admin_simpleshop_product_list');
		}
		
		// Form view.
		return [
			"product"  => $product,
			"formView" => $form->createView(),
		];
	}
	
	/**
	 * Show a list of images, to select the image for product.
	 * @param Product    $product
	 * @param Serializer $serializer
	 * @return array
	 *
	 * RouteName: app_admin_simpleshop_product_selectimagelist
	 * @Route("/admin/product/select-image/{product}", requirements={"product"="\d+|_id_"})
	 * @Template()
	 */
	public function selectImageListAction(Product $product, Serializer $serializer) {
		return [
			'product'    => $product,
			'uploadsDir' => Util::slashing($this->getParameter('app.uploads_load_directory'), Util::slashingAddRight),
			'images'     => $serializer->toJson($this->getContentFileRepository()->getImages()),
		];
	}
	
	/**
	 * Add the selected imageFile to the Product.
	 * @param Product $product
	 * @param File    $file
	 * @return RedirectResponse
	 *
	 * RouteName: app_admin_simpleshop_product_selectimagesubmit
	 * @Route("/admin/product/submit-selected-image/{product}/{file}", requirements={"image"="\d+|_id_"})
	 */
	public function selectImageSubmitAction(Product $product, File $file) {
		$product->setImage($file);
		$this->getDm()->flush();
		
		return $this->redirectToRoute('app_admin_simpleshop_product_update', [
			'product' => $product->getId(),
		]);
	}
	
}