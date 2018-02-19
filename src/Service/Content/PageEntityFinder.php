<?php

namespace App\Service\Content;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Content\Page;

/**
 * Class PAgeEntityFinder
 */
class PageEntityFinder {
	
	/** @var EntityManagerInterface $dm Doctrine manager. */
	protected $dm;
	
	/**
	 * TextFinder constructor.
	 * @param EntityManagerInterface $dm
	 */
	public function __construct(EntityManagerInterface $dm) {
		$this->dm = $dm;
	}
	
	/**
	 * Get the text entity by its code.
	 * It creates a new entity, if it wasn't created before.
	 * @param string $code Identifier string.
	 * @return Page|object
	 */
	public function get($code) {
		$pageEntity = $this->dm->getRepository(Page::class)->findOneBy(['code' => $code]);
		
		if ( ! $pageEntity instanceof Page) {
			throw new \Exception("Invalid page content code: {$code}");
		}
		
		return $pageEntity;
	}
	
}