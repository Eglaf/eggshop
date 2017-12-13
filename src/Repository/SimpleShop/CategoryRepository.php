<?php

namespace App\Repository\SimpleShop;

use App\Entity\SimpleShop\Category as CategoryEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CategoryRepository
 */
class CategoryRepository extends ServiceEntityRepository {
	
	/**
	 * Find all the categories with joined products.
	 * @return CategoryEntity[]
	 */
	public function findAllWithProducts() {
		return $this
			->createQueryBuilder('c')
			->addSelect('p')
			->leftJoin('c.products', 'p')
			->getQuery()
			->getResult();
	}
	
	/**
	 * CategoryRepository constructor.
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, CategoryEntity::class);
	}
	
}
