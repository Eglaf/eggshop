<?php

namespace App\Entity\SimpleShop;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SimpleShop\OrderRepository")
 */
class Order {
	
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
	 * @ORM\Column(name="comment", type="string", length=512, nullable=true, options={"fixed" = true})
	 */
	private $comment;
	
	/**
	 * Order constructor.
	 */
	public function __construct() {
		$this->date = new \DateTime();
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
	
}
