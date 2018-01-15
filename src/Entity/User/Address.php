<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address of user. Can be used as billing or shipping address... both of them are OneWayRelated from App\Entity\SimpleShop\Order.
 * @ORM\Entity(repositoryClass="App\Repository\User\AddressRepository")
 * @ORM\Table(name="user_address")
 */
class Address {
	
	private $user;
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var string Address named by the user.
	 * @ORM\Column(name="title", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $title;
	
	/**
	 * @var string
	 * @ORM\Column(name="city", type="string", length=255, options={"fixed" = true})
	 */
	private $city;
	
	/**
	 * @var string
	 * @ORM\Column(name="zipCode", type="string", length=16, options={"fixed" = true})
	 */
	private $zipCode;
	
	/**
	 * @var string
	 * @ORM\Column(name="street", type="string", length=128, options={"fixed" = true})
	 */
	private $street;
	
	/**
	 * @var string
	 * @ORM\Column(name="houseNumber", type="string", length=16, options={"fixed" = true})
	 */
	private $houseNumber;
	
	/**
	 * @var string
	 * @ORM\Column(name="floor", type="string", length=16, nullable=true, options={"fixed" = true})
	 */
	private $floor;
	
	/**
	 * @var string
	 * @ORM\Column(name="door", type="string", length=16, nullable=true, options={"fixed" = true})
	 */
	private $door;
	
	/**
	 * @var string
	 * @ORM\Column(name="doorBell", type="string", length=16, nullable=true, options={"fixed" = true})
	 */
	private $doorBell;
	
	
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
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * @param string $title
	 * @return Address
	 */
	public function setTitle($title) {
		$this->title = $title;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}
	
	/**
	 * @param string $city
	 * @return Address
	 */
	public function setCity($city) {
		$this->city = $city;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getZipCode() {
		return $this->zipCode;
	}
	
	/**
	 * @param string $zipCode
	 * @return Address
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}
	
	/**
	 * @param string $street
	 * @return Address
	 */
	public function setStreet($street) {
		$this->street = $street;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getHouseNumber() {
		return $this->houseNumber;
	}
	
	/**
	 * @param string $houseNumber
	 * @return Address
	 */
	public function setHouseNumber($houseNumber) {
		$this->houseNumber = $houseNumber;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getFloor() {
		return $this->floor;
	}
	
	/**
	 * @param string $floor
	 * @return Address
	 */
	public function setFloor($floor) {
		$this->floor = $floor;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getDoor() {
		return $this->door;
	}
	
	/**
	 * @param string $door
	 * @return Address
	 */
	public function setDoor($door) {
		$this->door = $door;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getDoorBell() {
		return $this->doorBell;
	}
	
	/**
	 * @param string $doorBell
	 * @return Address
	 */
	public function setDoorBell($doorBell) {
		$this->doorBell = $doorBell;
		
		return $this;
	}
	
}
