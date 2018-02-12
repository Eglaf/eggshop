<?php

namespace App\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\SimpleShop\Order;

/**
 * User entity.
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="user_user")
 *
 * todo Roles rework? Role entity or multiple strings in array?
 */
class User implements UserInterface, \Serializable {
	
	/**
	 * @var Order[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="App\Entity\SimpleShop\Order", mappedBy="user", cascade={"persist"})
	 */
	private $orders;
	
	/**
	 * @var Address[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="Address", mappedBy="user", cascade={"persist"})
	 */
	private $addresses;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Length(min=4, max=64)
	 * @ORM\Column(type="string", length=64, options={"fixed" = true})
	 */
	private $name;
	
	/**
	 * @var string
	 * @Assert\Email
	 * @ORM\Column(type="string", length=128, unique=true, options={"fixed" = true})
	 */
	private $email;
	
	/**
	 * @var string Temporally password.
	 * @Assert\NotBlank(groups={"registration"})
	 * @Assert\Length(min=8, max=64)
	 */
	private $plainPassword;
	
	/**
	 * @var string The password in hash format, stored in database.
	 * @ORM\Column(type="string", length=64, options={"fixed" = true})
	 */
	private $password;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=32, options={"fixed" = true})
	 */
	private $role;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=16, nullable=true, options={"fixed" = true})
	 */
	private $activationHash;
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Advanced methods - entity relations                        **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * User constructor.
	 */
	public function __construct() {
		$this->orders    = new ArrayCollection();
		$this->addresses = new ArrayCollection();
	}
	
	/**
	 * Add an Order.
	 * @param Order $order
	 * @return User
	 */
	public function addOrder(Order $order) {
		if ( ! $this->orders->contains($order)) {
			$this->orders[] = $order;
		}
		
		if ($order->getUser() !== $this) {
			$order->setUser($this);
		}
		
		return $this;
	}
	
	/**
	 * Remove an Order.
	 * @param Order $order
	 * @return $this
	 */
	public function removeOrder(Order $order) {
		if ($this->orders->contains($order)) {
			$this->orders->removeElement($order);
		}
		
		if ($order->getUser() === $this) {
			$order->setUser(NULL);
		}
		
		return $this;
	}
	
	/**
	 * Add an Order.
	 * @param Address $address
	 * @return User
	 */
	public function addAddress(Address $address) {
		if ( ! $this->addresses->contains($address)) {
			$this->addresses[] = $address;
		}
		
		if ($address->getUser() !== $this) {
			$address->setUser($this);
		}
		
		return $this;
	}
	
	/**
	 * Remove an Order.
	 * @param Address $address
	 * @return $this
	 */
	public function removeAddress(Address $address) {
		if ($this->addresses->contains($address)) {
			$this->addresses->removeElement($address);
		}
		
		if ($address->getUser() === $this) {
			$address->setUser(NULL);
		}
		
		return $this;
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Advanced methods - users                                   **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Would give back the salt, but it uses bcrypt... no salt needed.
	 * @return string|null
	 */
	public function getSalt() {
		return NULL;
	}
	
	public function getRoles() {
		return [$this->role];
	}
	
	public function eraseCredentials() {
	}
	
	/** @see \Serializable::serialize() */
	public function serialize() {
		return serialize([
			$this->id,
			$this->email,
			$this->password,
		]);
	}
	
	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			$this->email,
			$this->password,
			) = unserialize($serialized);
	}
	
	/**
	 * Email is the username here.
	 * @return string
	 */
	public function getUsername() {
		return $this->email;
	}
	
	/**
	 * Email is the username here.
	 * @param string $username
	 * @return User
	 */
	public function setUsername($username) {
		// $this->username = $username;
		
		return $this;
	}
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Basic methods                                              **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 * @return User
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @param string $password
	 * @return User
	 */
	public function setPassword($password) {
		$this->password = $password;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPlainPassword() {
		return $this->plainPassword;
	}
	
	/**
	 * @param mixed $plainPassword
	 * @return User
	 */
	public function setPlainPassword($plainPassword) {
		$this->plainPassword = $plainPassword;
		
		return $this;
	}
	
	/**
	 * @return Address[]|ArrayCollection
	 */
	public function getAddresses() {
		return $this->addresses;
	}
	
	/**
	 * @param Address[]|ArrayCollection $addresses
	 * @return User
	 */
	public function setAddresses($addresses) {
		$this->addresses = $addresses;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return mixed
	 */
	public function getRole() {
		return $this->role;
	}
	
	/**
	 * @param mixed $role
	 * @return User
	 */
	public function setRole($role) {
		$this->role = $role;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param mixed $email
	 * @return User
	 */
	public function setEmail($email) {
		$this->email = $email;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getActive() {
		return $this->active;
	}
	
	/**
	 * @return mixed
	 */
	public function isActive() {
		return $this->getActive();
	}
	
	/**
	 * @param mixed $active
	 * @return User
	 */
	public function setActive($active) {
		$this->active = $active;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getActivationHash() {
		return $this->activationHash;
	}
	
	/**
	 * @param string $activationHash
	 * @return User
	 */
	public function setActivationHash($activationHash) {
		$this->activationHash = $activationHash;
		
		return $this;
	}
	
	/**
	 * @return Order[]|ArrayCollection
	 */
	public function getOrders() {
		return $this->orders;
	}
	
	/**
	 * @param Order[]|ArrayCollection $orders
	 * @return User
	 */
	public function setOrders($orders) {
		$this->orders = $orders;
		
		return $this;
	}
	
}
