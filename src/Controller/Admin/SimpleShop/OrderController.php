<?php

namespace App\Controller\Admin\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity\SimpleShop\Order;
use App\Repository\SimpleShop\OrderRepository as OrderRepository;
use App\Form\Admin\SimpleShop\OrderType as OrderFormType;
use App\Service\Serializer;

/**
 * Class OrderController
 */
class OrderController extends AbstractController {
	
	/**
	 * List of Order Categories.
	 *
	 * RouteName: app_admin_simpleshop_order_list
	 * @Route("/admin/order/list")
	 * @Template
	 *
	 * @param Serializer      $serializer      Service to convert entities into json.
	 * @param OrderRepository $orderRepository Repository service of categories.
	 * @return array
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
	 * Create a Order.
	 *
	 * RouteName: app_admin_simpleshop_order_create
	 * @Route("/admin/order/create")
	 * @Template("admin/simple_shop/order/form.html.twig")
	 *
	 * @return array|RedirectResponse
	 */
	public function createAction() {
		$order = (new Order())
			->setDate(new \DateTime());
		
		return $this->form($order);
	}
	
	/**
	 * Update a Order.
	 *
	 * RouteName: app_admin_simpleshop_order_update
	 * @Route("/admin/order/update/{order}", requirements={"order"="\d+|_id_"})
	 * @Template("admin/simple_shop/order/form.html.twig")
	 *
	 * @param Order $order
	 * @return array|RedirectResponse
	 */
	public function updateAction(Order $order) {
		return $this->form($order);
	}
	
	/**
	 * Generate form view to Order.
	 * @param Order $order
	 * @return array|RedirectResponse
	 */
	protected function form(Order $order) {
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
						$this->addFlash('warning', "Duplicated products! ({$item->getProduct()->getLAbel()})");
						
						return $this->redirectToRoute('app_admin_simpleshop_order_update', ['order' => $order->getId()]);
					}
				}
			}
			
			return $this->redirectToRoute('app_admin_simpleshop_order_list');
		}
		
		// Form view.
		return [
			"order"    => $order,
			"formView" => $form->createView(),
		];
	}
	
}