<?php

namespace App\Controller\Site\User;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Service\Serializer;
use App\Controller\AbstractEggShopController;
use App\Entity\User\User,
	App\Entity\SimpleShop\Order;

/**
 * Class ProfileController
 * todo remove username? email only? or not?
 */
class ProfileController extends AbstractEggShopController {
	
	/**
	 * Main profile page.
	 *
	 * RouteName: app_site_user_profile_main
	 * @Route("/user/profile")
	 * @Template
	 */
	public function mainAction() {
		return [];
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Earlier orders                                             **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * List of earlier orders.
	 * @param Serializer $serializer
	 * @return array
	 *
	 * RouteName: app_site_user_profile_earlierorders
	 * @Route("/user/korabbi-rendelesek")
	 * @Template
	 */
	public function earlierOrdersAction(Serializer $serializer) {
		$orders = $this->getSimpleShopOrderRepository()->findExtendedByUser($this->getUser());
		
		return [
			'listAsJson' => $serializer->toJson($orders, [
				'attributes' => ['id', 'comment', 'date', 'priceSum', 'status' => ['label']],
			], [
				'date' => ['date'],
			]),
		];
	}
	
	/**
	 * Details of an earlier order.
	 * @param Order $order
	 * @return array
	 *
	 * RouteName: app_site_user_profile_earlierorderdetails
	 * @Route("/user/korabbi-rendeles-reszletek/{order}", requirements={"order"="\d+|_id_"})
	 * @Template
	 */
	public function earlierOrderDetailsAction(Order $order) {
		if ($order->getUser() !== $this->getUser()) {
			throw new \Exception('Relationship is missing between User and Order!');
		}
		
		return [
			'order' => $order,
		];
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Update user data                                            **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Details of an earlier order.
	 *
	 * RouteName: app_site_user_profile_userupdate
	 * @Route("/user/adatok-modositas")
	 * @Template
	 */
	public function userUpdateAction() { // userName & email & pw // todo ask for old pw all the time!
		return [];
	}
	
}