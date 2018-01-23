<?php

namespace App\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User entity.
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="user_user")
 *
 * todo Roles rework? Role entity or multiple strings in array?
 */
class User implements UserInterface, \Serializable {
	
	/**
	 * @var Address[]|ArrayCollection
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
	 * @ORM\Column(type="string", length=64, unique=true, options={"fixed" = true})
	 */
	private $username;
	
	/**
	 * @Assert\NotBlank()
	 * @Assert\Length(min=8, max=4096, groups={"registration"})
	 */
	private $plainPassword;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=64, options={"fixed" = true})
	 */
	private $password;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=32, options={"fixed" = true})
	 */
	private $role;
	
	/**
	 * @var string
	 * @Assert\Email
	 * @ORM\Column(type="string", length=128, unique=true, options={"fixed" = true})
	 */
	private $email;
	
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
	 * Advanced methods                                           **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	public function __construct() {
		$this->addresses = new ArrayCollection();
	}
	
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
	
	/**
	 * Wtf?
	 */
	public function eraseCredentials() {
	}
	
	/** @see \Serializable::serialize() */
	public function serialize() {
		return serialize([
			$this->id,
			$this->username,
			$this->password,
		]);
	}
	
	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			$this->username,
			$this->password,
			) = unserialize($serialized);
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Basic methods                                              **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @param string $username
	 * @return User
	 */
	public function setUsername($username) {
		$this->username = $username;
		
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

}
