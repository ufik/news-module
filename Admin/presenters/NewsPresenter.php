<?php

namespace AdminModule\NewsModule;

/**
 * Description of NewsPresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class NewsPresenter extends \AdminModule\BasePresenter {
	
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
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->idPage = $idPage;
	}
}