<?php

namespace App\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
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
	 * @ORM\Column(type="string", length=64, unique=true, options={"fixed" = true})
	 */
	private $username;
	
	/**
	 * @ORM\Column(type="string", length=64, options={"fixed" = true})
	 */
	private $password;
	
	/**
	 * @ORM\Column(type="string", length=32, options={"fixed" = true})
	 */
	private $role;
	
	/**
	 * @ORM\Column(type="string", length=128, unique=true, options={"fixed" = true})
	 */
	private $email;
	
	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive;
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Advanced methods                                           **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	public function __construct() {
		$this->isActive = TRUE;
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
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	

}
