<?php

namespace App\Repository\Content;

use App\Entity\Content\Text;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TextRepository extends ServiceEntityRepository {
	
	/**
	 * TextRepository constructor.
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Text::class);
	}
	
}
