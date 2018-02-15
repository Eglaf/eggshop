<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Config;

/**
 * Class ConfigReader
 * Read config from database.
 */
class ConfigReader {
	
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
	 * Get the value of config data.
	 * @param string $code Config identifier string.
	 * @return string Value.
	 */
	public function get($code) {
		$configEntity = $this->dm->getRepository(Config::class)->findOneBy(['code' => $code]);
		
		if ( ! $configEntity instanceof Config) {
			throw new \Exception("Config cannot be loaded from databese: {$code}!");
		}
		
		return $configEntity->getValue();
	}
	
}