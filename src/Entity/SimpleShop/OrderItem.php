<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\OrderItemRepository")
 */
class OrderItem {
	
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
	
}
