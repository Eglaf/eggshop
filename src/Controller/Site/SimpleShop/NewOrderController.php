<?php

namespace App\Controller\Site\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Util;
use App\Entity;
use App\Controller\AbstractEggShopController;
use App\Form\Site\SimpleShop as NewOrderFormType;
use App\Service\Content\TextEntityFinder;

/**
 * Class NewOrderController
 */
class NewOrderController extends AbstractEggShopController {
	
	/**
	 * RouteName: app_site_simpleshop_neworder_selectproducts
	 * @Route("/online-rendeles/termekek", methods={"GET"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @return array
	 */
	public function selectProductsAction(TextEntityFinder $textFinder, SessionInterface $session) {
		$productsForm = $this->createForm(NewOrderFormType\NewOrderType::class, NULL, [
			'method'          => 'POST',
			'action'          => $this->generateUrl('app_site_simpleshop_neworder_submitproducts'),
			'productEntities' => $this->getSimpleShopProductRepository()->findBy(['active' => TRUE]),
			'cart'            => $session->get('cart'),
		]);
		
		return [
			'categories'               => $this->getSimpleShopCategoryRepository()->findAllWithActiveProducts(),
			'form'                     => $productsForm->createView(),
			'beforeProductsTextEntity' => $textFinder->find('new_order_select_products_before'),
			'afterProductsTextEntity'  => $textFinder->find('new_order_select_products_after'),
		];
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_submitproducts
	 * @Route("/online-rendeles/termekek-rogiztes", methods={"POST"})
	 *
	 * @param SessionInterface $session
	 * @return RedirectResponse
	 */
	public function submitProductsAction(SessionInterface $session) {
		$formData = $this->getRq()->request->get('new_order');
		$products = $this->getSimpleShopProductRepository()->findBy(['active' => TRUE]);
		$cart     = [];
		
		/** @var Entity\SimpleSHop\Product $product */
		foreach ($products as $product) {
			if (isset($formData["product{$product->getId()}"]) && Util::isNaturalNumber($formData["product{$product->getId()}"])) {
				$cart[$product->getId()] = intval($formData["product{$product->getId()}"]);
			}
		}
		
		$session->set('cart', $cart);
		
		return $this->redirectToRoute('app_site_simpleshop_neworder_selectaddresses');
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_selectaddresses
	 * @Route("/online-rendeles/cimvalasztas", methods={"GET"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @return array
	 */
	public function selectAddressesAction(TextEntityFinder $textFinder, SessionInterface $session) {
		var_dump($session->get('cart'));
		
		die("TODO " . __LINE__);
		
		return [];
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_submitaddresses
	 * @Route("/online-rendeles/cimvalasztas-rogzites", methods={"POST"})
	 *
	 * @return RedirectResponse
	 */
	public function submitAddressesAction() {
		die("TODO " . __LINE__);
		
		return $this->redirectToRoute('app_site_simpleshop_neworder_confirmbeforeorder');
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_confirmbeforeorder
	 * @Route("online-rendeles/megerosites", methods={"GET"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @return array
	 */
	public function confirmBeforeOrderAction(TextEntityFinder $textFinder, SessionInterface $session) {
		die("TODO " . __LINE__);
		
		return [];
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_submitorder
	 * @Route("online-rendeles/mentes")
	 *
	 * @return RedirectResponse
	 */
	public function submitOrderAction() {
		die("TODO " . __LINE__);
		
		return $this->redirectToRoute('');
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_orderconfirmed
	 * @Route("/online-rendeles/mentve", methods={"GET"})
	 * @Template
	 *
	 * @return array
	 */
	public function orderConfirmedAction(TextEntityFinder $textFinder) {
		die("TODO " . __LINE__);
		
		return [];
	}
	
}