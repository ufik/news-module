<?php

namespace FrontendModule\NewsModule;

/**
 * Description of PagePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class NewsPresenter extends \FrontendModule\BasePresenter{
	
	private $repository;
	
	private $news;
	
	protected function startup() {
		parent::startup();
	
		$this->repository = $this->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($id){
		
		$this->news = $this->repository->findBy(array(
			'page' => $this->actualPage
		));
		
	}
	
	public function renderDefault($id){
		
		$this->template->news = $this->news;
		$this->template->id = $id;
	}
	
	
	public function newsBox($context, $fromPage){
		$page = $context->em->getRepository('WebCMS\PageModule\Doctrine\Page')->findOneBy(array(
			'page' => $fromPage
		));
		
		$text = '<h1>' . $fromPage->getTitle() . '</h1>';
		$text .= $page->getText();
		
		return $text;
	}
}