<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\ProductRepository")
 */
class Product {
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;
	
	/**
	 * @var string
	 * @ORM\Column(name="label", type="string", length=128, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * @var string
	 * @ORM\Column(name="description", type="string", length=512, options={"fixed" = true})
	 */
	private $description;
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->active;
	}
	
	/**
	 * @param bool $active
	 * @return Product
	 */
	public function setActive($active) {
		$this->active = $active;
		
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
	 * @return Product
	 */
	public function setLabel($label) {
		$this->label = $label;
		
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
	 * @return Product
	 */
	public function setDescription($description) {
		$this->description = $description;
		
		return $this;
	}
	
}
