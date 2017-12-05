<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\OrderStatusRepository")
 */
class OrderStatus {
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var string
	 * @ORM\Column(name="label", type="string", length=128, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * @var string Status code of order.
	 * @ORM\Column(name="code", type="string", length=64, options={"fixed" = true})
	 */
	private $code;
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @param string $label
	 * @return OrderStatus
	 */
	public function setLabel($label) {
		$this->label = $label;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @param string $code
	 * @return OrderStatus
	 */
	public function setCode($code) {
		$this->code = $code;
		
		return $this;
	}
	
}
