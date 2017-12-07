<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\CategoryRepository")
 */
class Category {
	
	/**
	 * @var Collection|Product[]
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="category", cascade={"persist"})
	 */
	protected $products;
	
	/**
	 * Category constructor.
	 */
	public function __construct() {
		$this->products = new ArrayCollection();
	}
	
	/**
	 * Add product to category.
	 * @param Product $product
	 * @return Category
	 */
	public function addProduct(Product $product) {
		if (!$this->products->contains($product)) {
			$this->products[] = $product;
		}
		
		if ($product->getCategory() !== $this) {
			$product->setCategory($this);
		}
		
		return $this;
	}
	
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
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Getters/Setters                                            **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
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
	
	/**
	 * @return Product[]
	 */
	public function getProducts() {
		return $this->products;
	}
	
}
