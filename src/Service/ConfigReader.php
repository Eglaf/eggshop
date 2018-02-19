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
	
	/** @var array $loaded Already loaded config values. */
	protected $loaded;
	
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
		if (isset($this->loaded[$code])) {
			return $this->loaded[$code];
		}
		
		$configEntity = $this->dm->getRepository(Config::class)->findOneBy(['code' => $code]);
		
		if ( ! $configEntity instanceof Config) {
			throw new \Exception("Config cannot be loaded from database: {$code}!");
		}
		
		$this->loaded[$code] = $configEntity->getValue();
		
		return $this->loaded[$code];
	}
	
	/**
	 * Preload config values.
	 * @param $codes
	 * @return $this
	 */
	public function preload($codes) {
		$configEntities = $this->dm->getRepository(Config::class)->findBy(['code' => $codes]);
		
		/** @var Config $configEntity */
		foreach ($configEntities as $configEntity) {
			$this->loaded[$configEntity->getCode()] = $configEntity->getValue();
		}
		
		return $this;
	}
	
}