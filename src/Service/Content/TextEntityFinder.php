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
	
	/** @var TextWithParams $textWithParams To replace parameter values in a text. */
	protected $textWithParams;
	
	/**
	 * TextFinder constructor.
	 * @param TextWithParams $textWithParams
	 * @param EntityManagerInterface $dm
	 */
	public function __construct(EntityManagerInterface $dm, TextWithParams $textWithParams) {
		$this->dm = $dm;
		$this->textWithParams = $textWithParams;
	}
	
	/**
	 * Get the text entity by its code.
	 * It creates a new entity, if it wasn't created before.
	 * @param string $code Identifier string.
	 * @return Text|object
	 */
	public function get($code) {
		$textEntity = $this->dm->getRepository(Text::class)->findOneBy(['code' => $code]);
		
		if ( ! $textEntity instanceof Text) {
			throw new \Exception("Invalid text content code: {$code}");
		}
		
		return $textEntity;
	}
	
	/**
	 * Get the text with parameters.
	 * @param string $code Identifier string.
	 * @param array $params Parameters to replace.
	 * @return string
	 */
	public function getStringWithParams($code, array $params) {
		$text = $this->get($code)->getText();
	
		return $this->textWithParams->getExtendedByArray($text, $params);
	}
	
}