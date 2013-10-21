<?php

namespace WebCMS\NewsModule\Doctrine;

use Doctrine\ORM\Mapping as orm;

/**
 * Description of News
 * @orm\Entity
 * @orm\Table(name="News")
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class Actuality extends \AdminModule\Doctrine\Entity {
	/**
	 * @orm\Column
	 */
	private $title;
	
	/**
	 * @orm\Column(type="text")
	 */
	private $perex;
	
	/**
	 * @orm\Column(type="text")
	 */
	private $text;
	
	/**
	 * @orm\ManyToOne(targetEntity="AdminModule\Page")
	 * @orm\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $page;
	
	public function getTitle() {
		return $this->title;
	}

	public function getPerex() {
		return $this->perex;
	}

	public function getText() {
		return $this->text;
	}

	public function getPage() {
		return $this->page;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setPerex($perex) {
		$this->perex = $perex;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function setPage($page) {
		$this->page = $page;
	}
}