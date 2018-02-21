<?php

namespace App\Repository\User;

use App\Entity\User\Address;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AddressRepository extends ServiceEntityRepository {
	
	/**
	 * AddressRepository constructor.
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Address::class);
	}
	
	/**
	 * @param User $user
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function queryByUser(User $user) {
		return $this->createQueryBuilder('a')
		            ->where('a.user = :user')
		            ->setParameter('user', $user);
	}
	
}
