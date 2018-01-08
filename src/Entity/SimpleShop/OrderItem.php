<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products ordered by user.
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\OrderItemRepository")
 * @ORM\Table(name="simpleshop_order_item")
 */
class OrderItem {
	
	/**
	 * @var Order
	 * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
	 * @ORM\JoinColumn(name="order_id", onDelete="SET NULL")
	 */
	private $order;
	
	/**
	 * The product that was ordered... although the price is stored to avoid changes in the price of order.
	 * @var Product
	 * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderItems")
	 * @ORM\JoinColumn(name="product_id", onDelete="SET NULL")
	 */
	private $product;
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var int
	 * @ORM\Column(type="integer", name="count")
	 */
	private $count;
	
	/**
	 * @var int The price of product when it was bought by the user.
	 * @ORM\Column(type="integer", name="price")
	 */
	private $price;
	
	
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
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}
	
	/**
	 * @param int $count
	 * @return OrderItem
	 */
	public function setCount($count) {
		$this->count = $count;
		
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
	 * @return OrderItem
	 */
	public function setPrice($price) {
		$this->price = $price;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getOrder() {
		return $this->order;
	}
	
	/**
	 * @param mixed $order
	 * @return OrderItem
	 */
	public function setOrder($order) {
		$this->order = $order;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getProduct() {
		return $this->product;
	}
	
	/**
	 * @param mixed $product
	 * @return OrderItem
	 */
	public function setProduct($product) {
		$this->product = $product;
		
		return $this;
	}

}
