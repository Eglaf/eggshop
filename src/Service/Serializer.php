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
	
	/** @var string Show value if no referenceMethod exists. */
	protected $valueWithoutReferenceMethod = 'NO-ID';
	
	/** @var string Date format. */
	protected $dateFormat = 'Y-m-d H:i:s';
	
	/**
	 * Convert objects into json.
	 * @param $data    object|array
	 * @param $context array To filter properties, pass: ['attributes' => ['id', 'label', 'active', 'category' => ['label']]]
	 * @param $normalizers array To normalize properties as, pass: ['date' => ['createdAt', 'modifiedAt']]
	 * @return string
	 */
	public function toJson($data, $context = [], $normalizers = []) {
		$normalizer = (new ObjectNormalizer())
			->setCircularReferenceLimit(1)
			->setCircularReferenceHandler(function($object) {
				return (Util::hasObjectMethod($object, $this->referenceMethod) ? Util::callObjectMethod($object, $this->referenceMethod) : $this->valueWithoutReferenceMethod);
			});
		
		// Normalize some properties as dateTime.
		if (isset($normalizers['date'])) {
			foreach ($normalizers['date'] as $dateProperty) {
				$normalizer->setCallbacks([$dateProperty => function($dateTime) {
					return $this->normalizeAsDateTimeCallback($dateTime);
				}]);
			}
		}
		
		$serializer = new SfSerializer([$normalizer], [new JsonEncoder()]);
		
		return $serializer->serialize($data, 'json', $context);
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
	
	/**
	 * @param string $dateFormat
	 * @return Serializer
	 */
	public function setDateFormat($dateFormat) {
		$this->dateFormat = $dateFormat;
		
		return $this;
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Normalizers                                                **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Normalise property as a dateTime.
	 * @param \DateTime $dateTime
	 * @return string
	 */
	protected function normalizeAsDateTimeCallback($dateTime) {
		return $dateTime instanceof \DateTime
			? $dateTime->format($this->dateFormat)
			: '-';
	}
	
	
}