<?php

namespace App\Entity\Content;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="content_file")
 */
class File {
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var \Symfony\Component\HttpFoundation\File\File The file itself.
	 * @Assert\NotBlank(message="Please upload a file.")
	 * @Assert\File(mimeTypes={"image/jpeg", "image/png"})
	 */
	private $file;
	
	/**
	 * @var string Storage name of the file in the uploads directory.
	 * @ORM\Column(name="storage_name", type="string", length=255, options={"fixed" = true})
	 */
	private $storageName;
	
	/**
	 * @var string
	 * @ORM\Column(name="mime_type", type="string", length=64, options={"fixed" = true})
	 */
	private $mimeType;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;
	
	/**
	 * @var string
	 * @ORM\Column(name="label", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $label;
	
	/**
	 * @var string
	 * @ORM\Column(name="description", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $description;
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return mixed
	 */
	public function getFile() {
		return $this->file;
	}
	
	/**
	 * @param mixed $file
	 * @return File
	 */
	public function setFile($file) {
		$this->file = $file;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getStorageName() {
		return $this->storageName;
	}
	
	/**
	 * @param string $storageName
	 * @return File
	 */
	public function setStorageName($storageName) {
		$this->storageName = $storageName;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}
	
	/**
	 * @param string $mimeType
	 * @return File
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
		
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->active;
	}
	
	/**
	 * @param bool $active
	 * @return File
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
	 * @return File
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
	 * @return File
	 */
	public function setDescription($description) {
		$this->description = $description;
		
		return $this;
	}
	
}
