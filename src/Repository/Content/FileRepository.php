<?php

namespace App\Repository\Content;

use App\Entity\Content\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FileRepository extends ServiceEntityRepository {
	
	/**
	 * Get files with image mimeType (jpg and png).
	 * @return File[]
	 */
	public function getImages() {
		return $this
			->createQueryBuilder('f')
			->where('f.active = true')
			->andWhere('f.mimeType = \'image/jpeg\'')
			->getQuery()
			->getResult();
	}
	
	/**
	 * FileRepository constructor.
	 */
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, File::class);
	}
	
}
