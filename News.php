<?php

namespace WebCMS\NewsModule;

/**
 * Description of Page
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class News extends \WebCMS\Module {
	
	protected $name = 'News';
	
	protected $author = 'Tomáš Voslař';
	
	protected $presenters = array(
		array(
			'name' => 'News',
			'frontend' => TRUE,
			'parameters' => TRUE
			),
		array(
			'name' => 'Photogallery',
			'frontend' => FALSE
			),
		array(
			'name' => 'Settings',
			'frontend' => FALSE
			)
	);
	
	protected $params = array(
		
	);
	
	public function __construct(){
		$this->addBox('News box', 'News', 'newsBox');
        $this->addBox('Reviews box', 'News', 'reviewsBox');
	}
	
}