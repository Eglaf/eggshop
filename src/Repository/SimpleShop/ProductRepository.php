<?php

namespace App\Repository\SimpleShop;

use App\Entity\SimpleShop\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository {
	
	/**
	 * Find all the categories with joined products.
	 * @return Product[]
	 */
	public function findAllWithCategory() {
		return $this
			->createQueryBuilder('p')
			->addSelect('c')
			->leftJoin('p.category', 'c')
			->getQuery()
			->getResult();
	}
	
	/**
	 * ProductRepository constructor.
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Product::class);
	}
	
}
