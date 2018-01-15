<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\OrderRepository")
 * @ORM\Table(name="simpleshop_order")
 */
class Order {
	
	/**
	 * @var OrderStatus
	 * @ORM\ManyToOne(targetEntity="OrderStatus", inversedBy="orders")
	 * @ORM\JoinColumn(name="status_id", onDelete="SET NULL")
	 */
	private $status;
	
	/**
	 * @var Collection|OrderItem[]
	 * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist"})
	 */
	private $items;
	
	/**
	 * ShippingAddress... OneWayRelated.
	 * @var \App\Entity\User\Address
	 * @ORM\ManyToOne(targetEntity="App\Entity\User\Address")
	 * @ORM\JoinColumn(name="shipping_address_id")
	 */
	private $shippingAddress;
	
	/**
	 * BillingAddress... OneWayRelated.
	 * @var \App\Entity\User\Address
	 * @ORM\ManyToOne(targetEntity="App\Entity\User\Address")
	 * @ORM\JoinColumn(name="billing_address_id", nullable=true)
	 */
	private $billingAddress;
	
	/**
	 * Order constructor.
	 */
	public function __construct() {
		$this->items = new ArrayCollection();
		$this->date  = new \DateTime();
	}
	
	/**
	 * Add an OrderItem to Order.
	 * @param OrderItem $item
	 * @return Order
	 */
	public function addItem(OrderItem $item) {
		if ( ! $this->items->contains($item)) {
			$this->items[] = $item;
		}
		
		if ($item->getOrder() !== $this) {
			$item->setOrder($this);
		}
		
		return $this;
	}
	
	/**
	 * Remove an OrderItem from Order.
	 * @param OrderItem $item
	 * @return $this
	 */
	public function removeItem(OrderItem $item) {
		if ($this->items->contains($item)) {
			$this->items->removeElement($item);
		}
		
		if ($item->getOrder() === $this) {
			$item->setOrder(NULL);
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
	 * @var \DateTime
	 * @ORM\Column(type="datetime", name="date")
	 */
	private $date;
	
	/**
	 * @var string
	 * @ORM\Column(name="comment", type="text", nullable=true)
	 */
	private $comment;
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Custom methods                                             **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Get the price summarized of the order.
	 * @return int
	 */
	public function getPriceSum() {
		$finalPrice = 0;
		foreach ($this->items as $item) {
			$finalPrice += $item->getCount() * $item->getPrice();
		}
		
		return $finalPrice;
	}
	
	
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
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * @param \DateTime $date
	 * @return Order
	 */
	public function setDate($date) {
		$this->date = $date;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 * @param string $comment
	 * @return Order
	 */
	public function setComment($comment) {
		$this->comment = $comment;
		
		return $this;
	}
	
	/**
	 * @return OrderStatus
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param OrderStatus|object $status
	 * @return Order
	 */
	public function setStatus($status) {
		$this->status = $status;
		
		return $this;
	}
	
	/**
	 * @return OrderItem[]|Collection
	 */
	public function getItems() {
		return $this->items;
	}
	
	/**
	 * @return \App\Entity\User\Address
	 */
	public function getShippingAddress() {
		return $this->shippingAddress;
	}
	
	/**
	 * @param \App\Entity\User\Address|object $shippingAddress
	 * @return Order
	 */
	public function setShippingAddress($shippingAddress) {
		$this->shippingAddress = $shippingAddress;
		
		return $this;
	}
	
	/**
	 * @return \App\Entity\User\Address
	 */
	public function getBillingAddress() {
		return $this->billingAddress;
	}
	
	/**
	 * @param \App\Entity\User\Address|object $billingAddress
	 * @return Order
	 */
	public function setBillingAddress($billingAddress) {
		$this->billingAddress = $billingAddress;
		
		return $this;
	}
	
}
