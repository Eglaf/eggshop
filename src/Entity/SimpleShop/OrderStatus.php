<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Status of order.
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\OrderStatusRepository")
 * @ORM\Table(name="simpleshop_order_status")
 */
class OrderStatus {
	
	/**
	 * @var Collection|Order[]
	 * @ORM\OneToMany(targetEntity="Order", mappedBy="status", cascade={"persist"})
	 */
	private $orders;
	
	/**
	 * OrderStatus constructor.
	 */
	public function __construct() {
		$this->orders = new ArrayCollection();
	}
	
	/**
	 * @param Order $order
	 * @return OrderStatus
	 */
	public function addOrders($order) {
		if (!$this->orders->contains($order)) {
			$this->orders[] = $order;
		}
		
		if ($order->getStatus() !== $this) {
			$order->setStatus($this);
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
	 * @var string
	 * @ORM\Column(name="label", type="string", length=128, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * Status code of order.
	 * @var string
	 * @ORM\Column(name="code", type="string", length=64, options={"fixed" = true})
	 */
	private $code;
	
	
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
	
	/**
	 * @return Order[]|Collection
	 */
	public function getOrders() {
		return $this->orders;
	}
	
}
