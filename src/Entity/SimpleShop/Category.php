<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\CategoryRepository")
 * @ORM\Table(name="simpleshop_category")
 */
class Category {
	
	/**
	 * @var Collection|Product[]
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="category", cascade={"persist"})
	 */
	private $products;
	
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
	 * @Assert\NotBlank(message="not_blank")
	 * @Assert\Length(min=4, max=64, minMessage="too_short", maxMessage="too_long")
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
