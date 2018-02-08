<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Content\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\ProductRepository")
 * @ORM\Table(name="simpleshop_product")
 */
class Product {
	
	/**
	 * @var Category
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
	 * @ORM\JoinColumn(name="category_id", onDelete="SET NULL")
	 */
	private $category;
	
	/**
	 * @var Collection|OrderItem[] Ordered item entities of this Product.
	 * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="product", cascade={"persist"})
	 */
	private $orderItems;
	
	/**
	 * @var File The image file.
	 * @ORM\ManyToOne(targetEntity="App\Entity\Content\File")
	 * @ORM\JoinColumn(name="image_id", onDelete="SET NULL")
	 */
	private $image;
	
	/**
	 * Product constructor.
	 */
	public function __construct() {
		$this->orderItems = new ArrayCollection();
	}
	
	/**
	 * Add OrderItem to Product.
	 * @param OrderItem $orderItem
	 * @return Product
	 */
	public function addOrderItem(OrderItem $orderItem) {
		if ( ! $this->orderItems->contains($orderItem)) {
			$this->orderItems[] = $orderItem;
		}
		
		if ($orderItem->getProduct() !== $this) {
			$orderItem->setProduct($this);
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
	 * @ORM\Column(name="label", type="string", length=128, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * @var string
	 * @ORM\Column(name="description", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $description;
	
	/**
	 * @var int
	 * @ORM\Column(type="integer", name="price")
	 */
	private $price;
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Getters/Setters                                            **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
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
	
	/**
	 * @return Category
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * @param Category $category
	 * @return Product
	 */
	public function setCategory($category) {
		$this->category = $category;
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getPrice() {
		return $this->price;
	}
	
	/**
	 * @param int $price
	 * @return Product
	 */
	public function setPrice($price) {
		$this->price = $price;
		
		return $this;
	}
	
	/**
	 * @return OrderItem[]|Collection
	 */
	public function getOrderItems() {
		return $this->orderItems;
	}
	
	/**
	 * @param OrderItem[]|Collection $orderItems
	 * @return Product
	 */
	public function setOrderItems($orderItems) {
		$this->orderItems = $orderItems;
		
		return $this;
	}
	
	/**
	 * @return File
	 */
	public function getImage() {
		return $this->image;
	}
	
	/**
	 * @param File $image
	 * @return Product
	 */
	public function setImage(File $image) {
		$this->image = $image;
		
		return $this;
	}
	
}
