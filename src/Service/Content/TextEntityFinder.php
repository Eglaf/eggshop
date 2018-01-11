<?php

namespace App\Service\Content;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Content\Text;

/**
 * Class TextEntityFinder
 */
class TextEntityFinder {
	
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
	 * @return Entity\Content\Text|object
	 */
	public function find($code) {
		$textEntity = $this->dm->getRepository(Text::class)->findOneBy(['code' => $code]);
		
		// Creates if it doesn't exist.
		if ( ! $textEntity instanceof Text) {
			$textEntity = (new Text())
				->setCode($code)
				->setText('');
			
			$this->dm->persist($textEntity);
			$this->dm->flush();
		}
		
		return $textEntity;
	}
	
}