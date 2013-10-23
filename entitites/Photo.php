<?php

namespace WebCMS\NewsModule\Doctrine;

use Doctrine\ORM\Mapping as orm;

/**
 * Description of Photo
 * @orm\Entity
 * @orm\Table(name="newsPhoto")
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class Photo extends \AdminModule\Doctrine\Entity{
	
	/**
	 * @orm\Column(type="text")
	 */
	private $title;
	
	/**
	 * @orm\ManyToOne(targetEntity="Actuality")
	 * @orm\JoinColumn(name="actuality_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $actuality;

	/**
	 * @orm\Column(type="text")
	 */
	private $path;
	
	/**
	 * @orm\Column(name="`default`",type="boolean")
	 */
	private $default;
	
	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getActuality() {
		return $this->actuality;
	}

	public function setActuality($actuality) {
		$this->actuality = $actuality;
	}

	public function getPath() {
		return $this->path;
	}

	public function setPath($path) {
		$this->path = $path;
	}
	
	public function getDefault() {
		return $this->default;
	}

	public function setDefault($default) {
		$this->default = $default;
	}
}