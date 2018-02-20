<?php

namespace App\Controller\Site\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Util;
use App\Entity;
use App\Controller\AbstractEggShopController;
use App\Form\Site\SimpleShop as SimpleShopFormType;
use App\Service\Content\TextEntityFinder;
use App\Service\ConfigReader;

/**
 * Class NewOrderController
 */
class NewOrderController extends AbstractEggShopController {
	
	/** @var TextEntityFinder */
	protected $textFinder;
	
	/** @var SessionInterface */
	protected $session;
	
	/** @var ConfigReader */
	protected $configReader;
	
	/**
	 * NewOrderController constructor.
	 * @param TextEntityFinder $textFinder
	 * @param SessionInterface $session
	 * @param ConfigReader     $configReader
	 */
	public function __construct(TextEntityFinder $textFinder, SessionInterface $session, ConfigReader $configReader) {
		$this->textFinder   = $textFinder;
		$this->session      = $session;
		$this->configReader = $configReader;
	}
	
	/**
	 * Select products.
	 * @return array
	 *
	 * RouteName: app_site_simpleshop_neworder_selectproducts
	 * @Route("/online-rendeles/termekek", methods={"GET"})
	 * @Template
	 */
	public function selectProductsAction() {
		$productsForm = $this->createForm(SimpleShopFormType\SelectProductsType::class, NULL, [
			'method'          => 'POST',
			'action'          => $this->generateUrl('app_site_simpleshop_neworder_submitproducts'),
			'productEntities' => $this->getSimpleShopProductRepository()->findBy(['active' => TRUE]),
			'cart'            => $this->session->get('cart'),
		]);
		
		$this->configReader->preload(['minimum-order-price-to-deliver', 'order-delivery-price', 'order-no-delivery-price-above-sum', 'admin-email', 'admin-phone']);
		
		return [
			'categories'         => $this->getSimpleShopCategoryRepository()->findAllWithActiveProducts(),
			'form'               => $productsForm->createView(),
			'imagePath'          => Util::slashing($this->getParameter('app.uploads_load_directory'), Util::slashingAddRight),
			'beforeProductsText' => $this->textFinder->getStringWithParams('new-order-select-products-before', $this->getTextParams()),
			'afterProductsText'  => $this->textFinder->getStringWithParams('new-order-select-products-after', $this->getTextParams()),
		];
	}
	
	/**
	 * Set selected products into session.
	 * @return RedirectResponse
	 *
	 * RouteName: app_site_simpleshop_neworder_submitproducts
	 * @Route("/online-rendeles/termekek-rogiztes", methods={"POST"})
	 */
	public function submitProductsAction() {
		$formData = $this->getRq()->request->get('select_products');
		$products = $this->getSimpleShopProductRepository()->findBy(['active' => TRUE]);
		$cart     = [];
		
		/** @var Entity\SimpleSHop\Product $product Products for session... Key is the product id, value is the count. */
		foreach ($products as $product) {
			if (isset($formData["product{$product->getId()}"]) && Util::isNaturalNumber($formData["product{$product->getId()}"])) {
				$cart[$product->getId()] = intval($formData["product{$product->getId()}"]);
			}
		}
		
		if (count($cart)) {
			$this->session->set('cart', $cart);
			
			return $this->redirectToRoute('app_site_simpleshop_neworder_selectaddresses');
		}
		else {
			return $this->redirectToRoute('app_site_simpleshop_neworder_selectproducts');
		}
	}
	
	/**
	 * Select delivery and billing addresses... it does the submit part too.
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_site_simpleshop_neworder_selectaddresses
	 * @Route("/online-rendeles/cimvalasztas", methods={"GET", "POST"})
	 * @Template
	 */
	public function selectAddressesAction() {
		// Without user, redirect to login.
		if ( ! ($this->getUser() instanceof Entity\User\User)) {
			return $this->redirectToRoute('app_site_user_login_login');
		}
		
		// Form.
		$addressesForm = $this->createForm(SimpleShopFormType\SelectAddressType::class, NULL, [
			'method' => 'POST',
		]);
		$addressesForm->handleRequest($this->getRq());
		
		// Submit.
		if ($addressesForm->isSubmitted() && $addressesForm->isValid()) {
			$this->addressToSession($addressesForm, 'delivery');
			$this->addressToSession($addressesForm, 'billing');
			
			if ($addressesForm->get('comment')->getData() && strlen($addressesForm->get('comment')->getData())) {
				$this->session->set('order_comment', $addressesForm->get('comment')->getData());
			}
			
			return $this->redirectToRoute('app_site_simpleshop_neworder_confirmbeforeorder');
		}
		
		// Price.
		$minimumPrice  = $this->configReader->get('minimum-order-price-to-deliver');
		$orderSumPrice = $this->getOrderSumPrice();
		
		return [
			'addressesForm'                         => $addressesForm->createView(),
			'beforeAddressesTextEntity'             => $this->textFinder->get('new-order-select-addresses-before'),
			'afterAddressesTextEntity'              => $this->textFinder->get('new-order-select-addresses-after'),
			'orderMinimumPrice'                     => $minimumPrice,
			'orderSumPrice'                         => $orderSumPrice,
			'warningSumPriceBelowDeliveryLimitText' => $this->textFinder->getStringWithParams('new-order-select-addresses-warning-below-delivery-limit', [
				'minimum-order-price-to-deliver' => $minimumPrice,
				'order-sum-price'                => $orderSumPrice,
			]),
		];
	}
	
	/**
	 * Show data to user and ask for confirm.
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_site_simpleshop_neworder_confirmbeforeorder
	 * @Route("online-rendeles/megerosites", methods={"GET"})
	 * @Template
	 */
	public function confirmBeforeOrderAction() {
		$products = $this->getCartProducts();
		
		return [
			'productsInCart'       => $products,
			'deliveryAddress'      => (Util::isNaturalNumber($this->session->get('deliveryAddressId')) ?
				$this->getUserAddressRepository()->find($this->session->get('deliveryAddressId')) : NULL),
			'billingAddress'       => (Util::isNaturalNumber($this->session->get('billingAddressId')) ?
				$this->getUserAddressRepository()->find($this->session->get('billingAddressId')) : NULL),
			'beforeTextEntity'     => $this->textFinder->get('new-order-confirm-before'),
			'afterTextEntity'      => $this->textFinder->get('new-order-confirm-after'),
			'deliveryPrice'        => $this->configReader->get('order-delivery-price'),
			'noDeliveryPriceAbove' => $this->configReader->get('order-no-delivery-price-above-sum'),
		];
	}
	
	/**
	 * Save the submitted order.
	 * @param TranslatorInterface $translator
	 * @return RedirectResponse
	 *
	 * RouteName: app_site_simpleshop_neworder_submitorder
	 * @Route("online-rendeles/mentes")
	 */
	public function submitOrderAction(TranslatorInterface $translator) {
		$cartProducts = $this->getCartProducts();
		
		// If no product is selected.
		if ( ! count($cartProducts)) {
			return $this->redirectToRoute('app_site_simpleshop_neworder_selectproducts');
		}
		
		$order = (new Entity\SimpleShop\Order())
			->setUser($this->getUser())
			->setStatus($this->getSimpleShopOrderStatusRepository()->find(1))
			->setDate(new \DateTime())
			->setShippingAddress($this->getOrderAddress('delivery'))
			->setBillingAddress($this->getOrderAddress('billing'));
		$this->getDm()->persist($order);
		
		/** @var Entity\SimpleShop\Product $product */
		foreach ($cartProducts as $product) {
			$orderItem = (new Entity\SimpleShop\OrderItem())
				->setProduct($product)
				->setOrder($order)
				->setCount($this->session->get('cart')[$product->getId()])
				->setPrice($product->getPrice());
			$this->getDm()->persist($orderItem);
		}
		
		$this->getDm()->flush();
		
		// Check new order.
		if ( ! Util::isNaturalNumber($order->getId())) {
			$this->addFlash('error', $translator->trans('message.error.order_wasnt_created'));
			
			return $this->redirectToRoute('app_site_simpleshop_neworder_selectproducts');
		}
		
		$this->session->set('cart', []);
		$this->session->set("deliveryAddressId", NULL);
		$this->session->set("newDeliveryAddress", NULL);
		$this->session->set("billingAddressId", NULL);
		$this->session->set("newBillingAddress", NULL);
		
		return $this->redirectToRoute('app_site_simpleshop_neworder_orderconfirmed');
	}
	
	/**
	 * Show the user how grateful we are.
	 * @return array
	 *
	 * RouteName: app_site_simpleshop_neworder_orderconfirmed
	 * @Route("/online-rendeles/mentve", methods={"GET"})
	 * @Template
	 */
	public function orderConfirmedAction() {
		return [
			'orderSubmittedTextEntity' => $this->textFinder->get('new-order-submit-confirmed'),
		];
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Protected                                                  **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Get the text parameters for selectProductAction.
	 * @return array
	 */
	protected function getTextParams() {
		return [
			'minimum-order-price-to-deliver'    => $this->configReader->get('minimum-order-price-to-deliver'),
			'order-delivery-price'              => $this->configReader->get('order-delivery-price'),
			'order-no-delivery-price-above-sum' => $this->configReader->get('order-no-delivery-price-above-sum'),
			'admin-email'                       => $this->configReader->get('admin-email'),
			'admin-phone'                       => $this->configReader->get('admin-phone'),
		];
	}
	
	/**
	 * Get the product entities of cart.
	 * @return Entity\SimpleShop\Product[]
	 */
	protected function getCartProducts() {
		return $this->getSimpleShopProductRepository()->findBy(['id' => array_keys($this->session->get('cart', []))]);
	}
	
	/**
	 * Set address data into session.
	 * @param FormInterface $addressesForm
	 * @param string        $type
	 */
	protected function addressToSession(FormInterface $addressesForm, $type) {
		$ucfType = ucfirst($type);
		
		// Delivery address set.
		if ($addressesForm->get("askingFor{$ucfType}Checkbox")->getData() === TRUE) {
			// New delivery address.
			if ($addressesForm->get("new{$ucfType}AddressCheckbox")->getData() === TRUE) {
				$this->session->set("new{$ucfType}Address", $addressesForm->get("new{$ucfType}Address")->getData());
				$this->session->set("{$type}AddressId", NULL);
			}
			// Existing delivery address.
			elseif (Util::isNaturalNumber($addressesForm->get("{$type}Address")->getData()->getId())) {
				$this->session->set("{$type}AddressId", $addressesForm->get("{$type}Address")->getData()->getId());
				$this->session->set("new{$ucfType}Address", NULL);
			}
			else {
				throw new \Exception("Invalid {$type} address data!");
			}
		}
		// No address needed.
		else {
			$this->session->set("new{$ucfType}Address", NULL);
			$this->session->set("{$type}AddressId", NULL);
		}
	}
	
	/**
	 * Get the order address from session. If new address was submitted, create the entity.
	 * @param string $type
	 * @return null|object
	 */
	protected function getOrderAddress($type) {
		$ucfType = ucfirst($type);
		
		// Existing address selected.
		if (Util::isNaturalNumber($this->session->get("{$type}AddressId"))) {
			return $this->getUserAddressRepository()->find($this->session->get("{$type}AddressId"));
		}
		// New address.
		elseif (is_array($this->session->get("new{$ucfType}Address")) && count($this->session->get("new{$ucfType}Address"))) {
			$newAddressData = $this->session->get("new{$ucfType}Address");
			
			$address = (new Entity\User\Address())
				->setUser($this->getUser())
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
	
	/**
	 * It gives back the summarized price of cart products.
	 * @return int
	 */
	protected function getOrderSumPrice() {
		$sum      = 0;
		$cart     = $this->session->get('cart');
		$products = $this->getCartProducts();
		
		foreach ($products as $product) {
			$sum += $cart[$product->getId()] * $product->getPrice();
		}
		
		return $sum;
	}
	
}
