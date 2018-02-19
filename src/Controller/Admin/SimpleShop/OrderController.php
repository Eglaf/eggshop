<?php

namespace App\Controller\Admin\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\SimpleShop\Order;
use App\Entity\User\User;
use App\Repository\SimpleShop\OrderRepository as OrderRepository;
use App\Form\Admin\SimpleShop\OrderType as OrderFormType;
use App\Service\Serializer;
use App\Service\ConfigReader;

/**
 * Class OrderController
 */
class OrderController extends AbstractController {
	
	/**
	 * List of Order Categories.
	 * @param Serializer      $serializer      Service to convert entities into json.
	 * @param OrderRepository $orderRepository Repository service of categories.
	 * @return array
	 *
	 * RouteName: app_admin_simpleshop_order_list
	 * @Route("/admin/order/list")
	 * @Template
	 */
	public function listAction(Serializer $serializer, OrderRepository $orderRepository) {
		$orderRows = $orderRepository->findWithData();
		
		return [
			'listAsJson' => $serializer->toJson($orderRows, [
				'attributes' => ['id', 'comment', 'date', 'priceSum', 'status' => ['label']],
			], [
				'date' => ['date'],
			]),
		];
	}
	
	/**
	 * Details of an Order.
	 * @param Order           $order
	 * @param ConfigReader    $configReader
	 * @param OrderRepository $orderRepository
	 * @return array
	 *
	 * RouteName: app_admin_simpleshop_order_details
	 * @Route("/admin/order/details/{order}", requirements={"order"="\d+|_id_"})
	 * @Template
	 */
	public function detailsAction(Order $order, ConfigReader $configReader, OrderRepository $orderRepository) {
		return [
			'order'                => $orderRepository->findDataOf($order),
			'deliveryPrice'        => $configReader->get('order-delivery-price'),
			'noDeliveryPriceAbove' => $configReader->get('order-no-delivery-price-above-sum'),
		];
	}
	
	/**
	 * Create an Order.
	 * It should come from the list of users page.
	 * @param User $user
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_simpleshop_order_create
	 * @Route("/admin/order/create/{user}", requirements={"user"="\d+|_id_"})
	 * @Template("admin/simple_shop/order/form.html.twig")
	 */
	public function createAction(User $user, TranslatorInterface $translator) {
		$order = (new Order())
			->setUser($user)
			->setDate(new \DateTime());
		
		return $this->form($order, $translator);
	}
	
	/**
	 * Update an Order.
	 * @param Order $order
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_simpleshop_order_update
	 * @Route("/admin/order/update/{order}", requirements={"order"="\d+|_id_"})
	 * @Template("admin/simple_shop/order/form.html.twig")
	 */
	public function updateAction(Order $order, TranslatorInterface $translator) {
		return $this->form($order, $translator);
	}
	
	/**
	 * Generate form view to Order.
	 * @param Order $order
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 */
	protected function form(Order $order, TranslatorInterface $translator) {
		// Original items.
		$originalItems = [];
		foreach ($order->getItems() as $originalItem) {
			$originalItems[] = $originalItem;
		}
		
		// Create form.
		$form = $this->createForm(OrderFormType::class, $order);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			// Add new item.
			foreach ($order->getItems() as $item) {
				$item->setOrder($order);
				
				// Set price of product.
				if ($item->getPrice() === NULL) {
					$item->setPrice($item->getProduct()->getPrice());
				}
			}
			
			// Remove item.
			foreach ($originalItems as $originalItem) {
				if ( ! $order->getItems()->contains($originalItem)) {
					$order->removeItem($originalItem);
				}
			}
			
			// Save.
			$this->getDm()->persist($order);
			$this->getDm()->flush();
			
			// Check duplicated items.
			foreach ($order->getItems() as $item) {
				foreach ($order->getItems() as $itemAgain) {
					if ($item->getId() !== $itemAgain->getId() && $item->getProduct()->getId() === $itemAgain->getProduct()->getId()) {
						$warning = $translator->trans('message.warning.order_with_duplicated_products', ['%product%' => $item->getProduct()->getLabel()]);
						$this->addFlash('warning', $warning);
						
						return $this->redirectToRoute('app_admin_simpleshop_order_update', ['order' => $order->getId()]);
					}
				}
			}
			
			$warning = $translator->trans('message.success.saved');
			$this->addFlash('success', $warning);
			
			return $this->redirectToRoute('app_admin_simpleshop_order_list');
		}
		
		// Form view.
		return [
			"order"    => $order,
			"formView" => $form->createView(),
		];
	}
	
}