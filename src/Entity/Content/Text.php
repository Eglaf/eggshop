<?php

namespace App\Entity\Content;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Content\TextRepository")
 * @ORM\Table(name="content_text")
 */
class Text {
	
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
	 * @var string
	 * @ORM\Column(name="text", type="text")
	 */
	private $text;
	
	/**
	 * @var array Possible parameters in the text.
	 * @ORM\Column(name="enabled_parameters", type="simple_array", nullable=true)
	 */
	private $enabledParameters;
	
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
	 * @return Text
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
	 * @return Text
	 */
	public function setText($text) {
		$this->text = $text;
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getEnabledParameters() {
		return $this->enabledParameters;
	}
	
	/**
	 * @param array $enabledParameters
	 * @return Text
	 */
	public function setEnabledParameters(array $enabledParameters = null) {
		$this->enabledParameters = $enabledParameters;
		
		return $this;
	}
	
}
