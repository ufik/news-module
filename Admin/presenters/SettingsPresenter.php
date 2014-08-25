<?php

namespace AdminModule\NewsModule;

/**
 * Description of PagePresenter
 * TODO create base presenter for page module
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class SettingsPresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){
	}
	
	public function createComponentSettingsForm(){
		
		$settings = array();
		$settings[] = $this->settings->get('Sidebar class', 'newsModule' . $this->actualPage->getId(), 'text', array());
		$settings[] = $this->settings->get('News posts count', 'newsModule' . $this->actualPage->getId(), 'text', array());
		
		return $this->createSettingsForm($settings);
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->config = $this->settings->getSection('newsModule');
		$this->template->idPage = $idPage;
	}
	
	
}
