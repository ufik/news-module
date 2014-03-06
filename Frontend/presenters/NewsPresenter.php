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
		
		
	}
	
	public function actionDefault($id){
		
		$this->news = $this->repository->findBy(array(
			'page' => $this->actualPage
		), array('date' => 'DESC'));
		
		$this->template->sidebar = array('asdf', 'asdf');
	}
	
	public function renderDefault($id){
		
		$detail = $this->getParameter('parameters');
		$actuality = NULL;
		
		if(count($detail) > 0){
			$actuality = $this->repository->findOneBySlug($detail[0]);
			
			if(!is_object($actuality)){
				$this->redirect('default', array(
					'path' => $this->actualPage->getPath(),
					'abbr' => $this->abbr
				));
			}
			
			$this->addToBreadcrumbs($this->actualPage->getId(), 
				'News',
				'News',
				$actuality->getTitle(),
				$this->actualPage->getPath() . '/' . $actuality->getSlug()
			);
		}
		
		parent::beforeRender();
		
		$this->template->sidebar = $this->sidebar($this->news);
		$this->template->actuality = $actuality;
		$this->template->news = $this->news;
		$this->template->id = $id;
	}
	
	public function sidebar($items){
		$class = $this->settings->get('Sidebar class', 'newsModule' . $this->actualPage->getId(), 'text', array())->getValue();
		$sidebar = '<ul class="' . $class . '">';
		
		$parameters = $this->getParameter('parameters');
		$detail = count($parameters) > 0 ? $parameters[0] : '';
		
		foreach($items as $item){
			$selected = $detail == $item->getSlug() ? 'class="active"' : '';
			
			$sidebar .= '<li ' . $selected . '><a ' . $selected . ' href="' . $this->link('default', array(
				'path' => $this->actualPage->getPath(),
				'abbr' => $this->abbr,
				'parameters' => array($item->getSlug())
			)) . '">' . $item->getTitle() . '</a></li>';
		}
		
		return $sidebar .= '</ul>';
	}
	
	public function newsBox($context, $fromPage){
		
		$repository = $context->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
		$actualities = $repository->findBy(array(
			'page' => $fromPage
		), array('date' => 'DESC'));
		
		$template = $context->createTemplate();
		$template->setFile('../app/templates/news-module/News/box.latte');
		$template->actualities = $actualities;
		$template->link = $context->link(':Frontend:News:News:default', array(
			'id' => $fromPage->getId(),
			'path' => $fromPage->getPath(),
			'abbr' => $context->abbr
				));
		
		return $template;
	}
}
