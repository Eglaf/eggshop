<?php

namespace App\Controller;

use App\Egf\Ancient\AbstractController;
use App\Entity;

/**
 * Class AbstractEggShopController
 */
class AbstractEggShopController extends AbstractController {
	
	/** @return \App\Repository\SimpleShop\CategoryRepository|\Doctrine\ORM\EntityRepository */
	protected function getSimpleShopCategoryRepository() {
		return $this->getDm()->getRepository(Entity\SimpleShop\Category::class);
	}
	
	/** @return \App\Repository\SimpleShop\ProductRepository|\Doctrine\ORM\EntityRepository */
	protected function getSimpleShopProductRepository() {
		return $this->getDm()->getRepository(Entity\SimpleShop\Product::class);
	}
	
	/** @return \App\Repository\SimpleShop\OrderRepository|\Doctrine\ORM\EntityRepository */
	protected function getSimpleShopOrderRepository() {
		return $this->getDm()->getRepository(Entity\SimpleShop\Order::class);
	}
	
	/** @return \App\Repository\SimpleShop\OrderStatusRepository|\Doctrine\ORM\EntityRepository */
	protected function getSimpleShopOrderStatusRepository() {
		return $this->getDm()->getRepository(Entity\SimpleShop\OrderStatus::class);
	}
	
	/** @return \App\Repository\Content\TextRepository|\Doctrine\ORM\EntityRepository */
	protected function getContentTextRepository() {
		return $this->getDm()->getRepository(Entity\Content\Text::class);
	}
	
	/** @return \App\Repository\User\AddressRepository|\Doctrine\ORM\EntityRepository */
	protected function getUserAddressRepository() {
		return $this->getDm()->getRepository(Entity\User\Address::class);
	}
	
	
}