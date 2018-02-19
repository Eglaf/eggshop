<?php

namespace App\Repository\Content;

use App\Entity\Content\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PageRepository extends ServiceEntityRepository {
	
	/**
	 * PageRepository constructor.
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Page::class);
	}
	
}
