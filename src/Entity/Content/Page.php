<?php

namespace App\Entity\Content;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Content\PageRepository")
 * @ORM\Table(name="content_page")
 */
class Page {
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var string Admin can identify the content text by this code.
	 * @ORM\Column(name="code", type="string", length=128, options={"fixed" = true})
	 */
	private $code;
	
	/**
	 * @var string Page title.
	 * @ORM\Column(name="title", type="string", length=255, options={"fixed" = true})
	 */
	private $title;
	
	/**
	 * @var string
	 * @ORM\Column(name="description", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $description;
	
	/**
	 * @var string
	 * @ORM\Column(name="keywords", type="string", length=255, nullable=true, options={"fixed" = true})
	 */
	private $keywords;
	
	/**
	 * @var string
	 * @ORM\Column(name="text", type="text")
	 */
	private $text;
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @param string $code
	 * @return Page
	 */
	public function setCode($code) {
		$this->code = $code;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * @param string $text
	 * @return Page
	 */
	public function setText($text) {
		$this->text = $text;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * @param string $title
	 * @return Text
	 */
	public function setTitle($title) {
		$this->title = $title;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * @param mixed $description
	 * @return Page
	 */
	public function setDescription($description) {
		$this->description = $description;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getKeywords() {
		return $this->keywords;
	}
	
	/**
	 * @param mixed $keywords
	 * @return Page
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
		
		return $this;
	}
	
}
