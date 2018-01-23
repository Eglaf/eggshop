<?php

namespace App\Controller\Site\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Util;
use App\Entity;
use App\Controller\AbstractEggShopController;
use App\Form\Site\SimpleShop as SimpleShopFormType;
use App\Service\Content\TextEntityFinder;

/**
 * Class NewOrderController
 *
 * todo check login (redirect to reg/login) (there if there are products, +1 button to back to new order)
 * todo check at least 1 product
 * todo check minimum order value
 * todo address form data
 * todo order ENTITY shippingAddress REWRITE TO deliveryAddress
 */
class NewOrderController extends AbstractEggShopController {
	
	/**
	 * Select products.
	 *
	 * RouteName: app_site_simpleshop_neworder_selectproducts
	 * @Route("/online-rendeles/termekek", methods={"GET"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @return array
	 */
	public function selectProductsAction(TextEntityFinder $textFinder, SessionInterface $session) {
		$productsForm = $this->createForm(SimpleShopFormType\NewOrderType::class, NULL, [
			'method'          => 'POST',
			'action'          => $this->generateUrl('app_site_simpleshop_neworder_submitproducts'),
			'productEntities' => $this->getSimpleShopProductRepository()->findBy(['active' => TRUE]),
			'cart'            => $session->get('cart'),
		]);
		
		return [
			'categories'               => $this->getSimpleShopCategoryRepository()->findAllWithActiveProducts(),
			'form'                     => $productsForm->createView(),
			'beforeProductsTextEntity' => $textFinder->get('new-order-select-products-before'),
			'afterProductsTextEntity'  => $textFinder->get('new-order-select-products-after'),
		];
	}
	
	/**
	 * Set selected products into session.
	 *
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
		
		/** @var Entity\SimpleSHop\Product $product Products for session... Key is the product id, value is the count. */
		foreach ($products as $product) {
			if (isset($formData["product{$product->getId()}"]) && Util::isNaturalNumber($formData["product{$product->getId()}"])) {
				$cart[$product->getId()] = intval($formData["product{$product->getId()}"]);
			}
		}
		
		$session->set('cart', $cart);
		
		return $this->redirectToRoute('app_site_simpleshop_neworder_selectaddresses');
	}
	
	/**
	 * Select delivery and billing addresses... it does the submit part too.
	 *
	 * RouteName: app_site_simpleshop_neworder_selectaddresses
	 * @Route("/online-rendeles/cimvalasztas", methods={"GET", "POST"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @return array|RedirectResponse
	 */
	public function selectAddressesAction(TextEntityFinder $textFinder, SessionInterface $session) {
		$addressesForm = $this->createForm(SimpleShopFormType\SelectAddressType::class, NULL, [
			'method' => 'POST',
		]);
		$addressesForm->handleRequest($this->getRq());
		
		if ($addressesForm->isSubmitted() && $addressesForm->isValid()) {
			$this->addressToSession($session, $addressesForm, 'delivery');
			$this->addressToSession($session, $addressesForm, 'billing');
			
			return $this->redirectToRoute('app_site_simpleshop_neworder_confirmbeforeorder');
		}
		
		return [
			'addressesForm'             => $addressesForm->createView(),
			'beforeAddressesTextEntity' => $textFinder->get('new-order-select-addresses-before'),
			'afterAddressesTextEntity'  => $textFinder->get('new-order-select-addresses-after'),
		];
	}
	
	/**
	 * Show data to user and ask for confirm.
	 *
	 * RouteName: app_site_simpleshop_neworder_confirmbeforeorder
	 * @Route("online-rendeles/megerosites", methods={"GET"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @return array
	 */
	public function confirmBeforeOrderAction(TextEntityFinder $textFinder, SessionInterface $session) {
		return [
			'productsInCart'   => $this->getCartProducts($session),
			'deliveryAddress'  => (Util::isNaturalNumber($session->get('deliveryAddressId')) ?
				$this->getUserAddressRepository()->find($session->get('deliveryAddressId')) : NULL),
			'billingAddress'   => (Util::isNaturalNumber($session->get('billingAddressId')) ?
				$this->getUserAddressRepository()->find($session->get('billingAddressId')) : NULL),
			'beforeTextEntity' => $textFinder->get('new-order-confirm-before'),
			'afterTextEntity'  => $textFinder->get('new-order-confirm-after'),
		];
	}
	
	/**
	 * Save the submitted order.
	 *
	 * RouteName: app_site_simpleshop_neworder_submitorder
	 * @Route("online-rendeles/mentes")
	 *
	 * @param SessionInterface $session
	 * @return RedirectResponse
	 */
	public function submitOrderAction(SessionInterface $session) {
		$order = (new Entity\SimpleShop\Order())
			->setStatus($this->getSimpleShopOrderStatusRepository()->find(1))
			->setDate(new \DateTime())
			->setShippingAddress($this->getOrderAddress($session, 'delivery'))
			->setBillingAddress($this->getOrderAddress($session, 'billing'));
		$this->getDm()->persist($order);
		
		$cartProducts = $this->getCartProducts($session);
		foreach ($cartProducts as $product) {
			$orderItem = (new Entity\SimpleShop\OrderItem())
				->setProduct($product)
				->setOrder($order)
				->setCount($session->get('cart')[$product->getId()])
				->setPrice($product->getPrice());
			$this->getDm()->persist($orderItem);
		}
		
		$this->getDm()->flush();
		
		$session->set('cart', []);
		
		return $this->redirectToRoute('app_site_simpleshop_neworder_orderconfirmed');
	}
	
	/**
	 * Show the user how grateful we are.
	 *
	 * RouteName: app_site_simpleshop_neworder_orderconfirmed
	 * @Route("/online-rendeles/mentve", methods={"GET"})
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @return array
	 */
	public function orderConfirmedAction(TextEntityFinder $textFinder) {
		return [
			'orderSubmittedTextEntity' => $textFinder->get('new-order-submit-confirmed'),
		];
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Protected                                                  **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Get the product entities of cart.
	 * @param SessionInterface $session
	 * @return Entity\SimpleShop\Product[]
	 */
	protected function getCartProducts(SessionInterface $session) {
		return $this->getSimpleShopProductRepository()->findBy(['id' => array_keys($session->get('cart', []))]);
	}
	
	/**
	 * Set address data into session.
	 * @param SessionInterface $session
	 * @param FormInterface    $addressesForm
	 * @param string           $type
	 */
	protected function addressToSession(SessionInterface $session, FormInterface $addressesForm, $type) {
		$ucfType = ucfirst($type);
		
		// Delivery address set.
		if ($addressesForm->get("askingFor{$ucfType}Checkbox")->getData() === TRUE) {
			// New delivery address.
			if ($addressesForm->get("new{$ucfType}AddressCheckbox")->getData() === TRUE) {
				$session->set("new{$ucfType}Address", $addressesForm->get("new{$ucfType}Address")->getData());
			}
			// Existing delivery address.
			elseif (Util::isNaturalNumber($addressesForm->get("{$type}Address")->getData()->getId())) {
				$session->set("{$type}AddressId", $addressesForm->get("{$type}Address")->getData()->getId());
			}
			else {
				throw new \Exception("Invalid {$type} address data!");
			}
		}
	}
	
	/**
	 * Get the order address from session. If new address was submitted, create the entity.
	 * @param SessionInterface $session
	 * @param string           $type
	 * @return null|object
	 */
	protected function getOrderAddress(SessionInterface $session, $type) {
		$ucfType = ucfirst($type);
		
		// Existing address selected.
		if (Util::isNaturalNumber($session->get("{$type}AddressId"))) {
			return $this->getUserAddressRepository()->find($session->get("{$type}AddressId"));
		}
		// New address.
		elseif (is_array($session->get("new{$ucfType}Address")) && count($session->get("new{$ucfType}Address"))) {
			$newAddressData = $session->get("new{$ucfType}Address");
			
			$address = (new Entity\User\Address())
				->setTitle($newAddressData['title'])
				->setCity($newAddressData['city'])
				->setZipCode($newAddressData['zipCode'])
				->setStreet($newAddressData['street'])
				->setHouseNumber($newAddressData['houseNumber'])
				->setDoorBell($newAddressData['doorBell'])
				->setFloor($newAddressData['floor'])
				->setDoor($newAddressData['door']);
			
			$this->getDm()->persist($address);
			
			return $address;
		}
		
		return NULL;
	}
	
}
