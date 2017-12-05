<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\CategoryRepository")
 */
class Category {
	
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
	 * @ORM\Column(name="label", type="string", length=255, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * @return mixed
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
	 * @return Category
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
	 * @return Category
	 */
	public function setLabel($label) {
		$this->label = $label;
		
		return $this;
	}
	
}
