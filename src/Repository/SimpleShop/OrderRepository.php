<?php

namespace App\Repository\SimpleShop;

use App\Entity\SimpleShop\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Entity\User\User;

class OrderRepository extends ServiceEntityRepository {
	
	public function findExtendedByUser(User $user) {
		return $this
			->createQueryBuilder('o')
			->addSelect('s')
			->leftJoin('o.status', 's')
			->addSelect('i')
			->leftJoin('o.items', 'i')
			->where('o.user = :user')
			->setParameter('user', $user->getId())
			->getQuery()
			->getResult();
	}
	
	/**
	 * @return mixed
	 */
	public function findWithData() {
		return $this
			->createQueryBuilder('o')
			->addSelect('s')
			->leftJoin('o.status', 's')
			->addSelect('i')
			->leftJoin('o.items', 'i')
			->getQuery()
			->getResult();
	}
	
	/**
	 * OrderRepository constructor.
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Order::class);
	}
	
}
