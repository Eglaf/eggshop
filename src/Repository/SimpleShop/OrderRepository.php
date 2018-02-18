<?php

namespace App\Repository\SimpleShop;

use App\Entity\SimpleShop\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Egf\Util;
use App\Entity\User\User;

class OrderRepository extends ServiceEntityRepository {
	
	/**
	 * Load order with related data.
	 * @param Order|integer $order
	 * @return Order
	 */
	public function findDataOf($order) {
		if ($order instanceof Order) {
			$orderId = $order->getId();
		}
		elseif (Util::isNaturalNumber($order)) {
			$orderId = $order;
		}
		else {
			throw new \Exception('Invalid order id!');
		}
		
		return $this
			->createQueryBuilder('o')
			->addSelect('u')
			->leftJoin('o.user', 'u')
			->addSelect('s')
			->leftJoin('o.status', 's')
			->addSelect('i')
			->leftJoin('o.items', 'i')
			->addSelect('p')
			->leftJoin('i.product', 'p')
			->addSelect('ad')
			->leftJoin('o.shippingAddress', 'ad')
			->addSelect('ab')
			->leftJoin('o.billingAddress', 'ab')
			->where('o.id = :orderId')
			->setParameter('orderId', $orderId)
			->getQuery()
			->getOneOrNullResult();
	}
	
	/**
	 * Find Orders by User.
	 * @param User $user
	 * @return mixed
	 */
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
