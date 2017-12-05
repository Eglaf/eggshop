<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\AddressTypeRepository")
 */
class AddressType {
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var string Identifier code of the type.
	 * @ORM\Column(name="code", type="string", length=128, options={"fixed" = true})
	 */
	private $code;
	
	/**
	 * @var string
	 * @ORM\Column(name="label", type="string", length=128, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * @return int
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
	 * @return AddressType
	 */
	public function setCode($code) {
		$this->code = $code;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @param string $label
	 * @return AddressType
	 */
	public function setLabel($label) {
		$this->label = $label;
		
		return $this;
	}
	
}
