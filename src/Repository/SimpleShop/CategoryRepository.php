<?php

namespace App\Repository\SimpleShop;

use App\Entity\SimpleShop\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CategoryRepository
 */
class CategoryRepository extends ServiceEntityRepository {
	
	/**
	 * Find all the categories with joined products.
	 * @return Category[]
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
	 * Find all the categories with joined products.
	 * @return Category[]
	 */
	public function findAllWithActiveProducts() {
		return $this
			->createQueryBuilder('c')
			->leftJoin('c.products', 'p')
			->addSelect('p')
			->leftJoin('p.image', 'i')
			->addSelect('i')
			->where('p.active = true')
			->getQuery()
			->getResult();
	}
	
	/**
	 * CategoryRepository constructor.
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Category::class);
	}
	
}
