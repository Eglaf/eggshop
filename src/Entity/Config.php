<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Some config data. Max 255 characters.
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 * @ORM\Table(name="config")
 */
class Config {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var string
	 * @ORM\Column(name="code", type="string", length=255, options={"fixed" = true})
	 */
	private $code;
	
	/**
	 * @var string
	 * @ORM\Column(name="value", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $value;
	
	/**
	 * @var string Description of the config field.
	 * @ORM\Column(name="description", type="string", length=255, options={"fixed" = true})
	 */
	private $description;
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @param string $code
	 * @return Config
	 */
	public function setCode($code) {
		$this->code = $code;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @param string $value
	 * @return Config
	 */
	public function setValue($value) {
		$this->value = $value;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * @param string $description
	 * @return Config
	 */
	public function setDescription($description) {
		$this->description = $description;
		
		return $this;
	}
	
}
