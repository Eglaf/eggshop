<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SfSerializer;

use App\Egf\Util;

/**
 * Class Serializer
 */
class Serializer {
	
	/** @var string Call object method in case of circular reference */
	protected $referenceMethod = 'getId';
	
	/** @var string SHow value if no referenceMethod exists. */
	protected $valueWithoutReferenceMethod = 'NO-ID';
	
	/**
	 * Convert objects into json.
	 * @param $data object|array
	 * @return string
	 */
	public function toJson($data) {
		$normalizer = (new ObjectNormalizer())
			->setCircularReferenceLimit(1)
			->setCircularReferenceHandler(function ($object) {
				return (Util::hasObjectMethod($object, $this->referenceMethod) ? Util::callObjectMethod($object, $this->referenceMethod) : $this->valueWithoutReferenceMethod);
			});
		
		$serializer = new SfSerializer([$normalizer], [new JsonEncoder()]);
		
		return $serializer->serialize($data, 'json');
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Setters                                                    **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * @param string $referenceMethod
	 * @return Serializer
	 */
	public function setReferenceMethod($referenceMethod) {
		$this->referenceMethod = $referenceMethod;
		
		return $this;
	}
	
	/**
	 * @param string $valueWithoutReferenceMethod
	 * @return Serializer
	 */
	public function setValueWithoutReferenceMethod($valueWithoutReferenceMethod) {
		$this->valueWithoutReferenceMethod = $valueWithoutReferenceMethod;
		
		return $this;
	}

	
	
}