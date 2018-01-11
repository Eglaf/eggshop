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
	
	/** @return \App\Repository\Content\TextRepository|\Doctrine\ORM\EntityRepository */
	protected function getContentTextRepository() {
		return $this->getDm()->getRepository(Entity\Content\Text::class);
	}
	
	
	
}