<?php

namespace FrontendModule\NewsModule;

/**
 * Description of PagePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class NewsPresenter extends \FrontendModule\BasePresenter {

    private $repository;
    private $news;
 	private $ppp;
    private $paginator;
    private $page;

    protected function startup() {
	parent::startup();

	$this->repository = $this->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
    }

    protected function beforeRender() {
	
    }

    public function actionDefault($id) {

	$this->page = $this->getParameter('p') ? $this->getParameter('p') : 0;
	$this->ppp = $this->settings->get('News posts count', 'newsModule' . $this->actualPage->getId(), 'text', array())->getValue();

	$this->ppp = $this->ppp ? $this->ppp : 10000;

	$this->paginator = new \Nette\Utils\Paginator;
	$this->paginator->setItemCount(count($this->news = $this->repository->findAll())); 
	$this->paginator->setItemsPerPage($this->ppp); 
	$this->paginator->setPage($this->page == 0 ? $this->page + 1 : $this->page); 

	$this->news = $this->repository->findBy(array(
	    'page' => $this->actualPage
	    ), array('rank' => 'ASC'), $this->paginator->getLength(), $this->paginator->getOffset()
	);

    }

    public function renderDefault($id) {

	$detail = $this->getParameter('parameters');
	$actuality = NULL;

	if (count($detail) > 0) {
	    $actuality = $this->repository->findOneBySlug($detail[0]);

	    if (!is_object($actuality)) {
		$this->redirect('default', array(
		    'path' => $this->actualPage->getPath(),
		    'abbr' => $this->abbr
		));
	    }

	    if ($this->isAjax()) {
		$this->payload->title = $this->template->seoTitle;
		$this->payload->url = $this->link('default', array(
		    'path' => $this->actualPage->getPath(),
		    'abbr' => $this->abbr,
		    'parameters' => array(\Nette\Utils\Strings::webalize($actuality->getTitle()))
			));
		$this->payload->nameSeo = \Nette\Utils\Strings::webalize($actuality->getTitle());
		$this->payload->name = $actuality->getTitle();
	    }
	    
	    $this->addToBreadcrumbs($this->actualPage->getId(), 'News', 'News', $actuality->getTitle(), $this->actualPage->getPath() . '/' . $actuality->getSlug()
	    );
	}

	parent::beforeRender();

	$this->template->paginator = $this->paginator;
	$this->template->page = $this->page;

	if ($this->settings->get('Overwrite sidebar?', 'newsModule' . $this->actualPage->getId(), 'checkbox', array())->getValue()) {
		$this->template->sidebar = $this->sidebar($this->news);
	} else {
		$this->template->newsSidebar = $this->sidebar($this->news);
	}
	
	$this->template->actuality = $actuality;
	$this->template->news = $this->news;
	$this->template->id = $id;
    }

    public function sidebar($items) {
	$class = $this->settings->get('Sidebar class', 'newsModule' . $this->actualPage->getId(), 'text', array())->getValue();
	$sidebar = '<ul class="' . $class . '">';

	$parameters = $this->getParameter('parameters');
	$detail = count($parameters) > 0 ? $parameters[0] : '';

	foreach ($items as $item) {
	    $selected = $detail == $item->getSlug() ? 'class="active"' : '';

	    $sidebar .= '<li ' . $selected . '><a ' . $selected . ' href="' . $this->link('default', array(
		    'path' => $this->actualPage->getPath(),
		    'abbr' => $this->abbr,
		    'parameters' => array($item->getSlug())
		)) . '">' . $item->getTitle() . '</a></li>';
	}

	return $sidebar .= '</ul>';
    }

    public function newsBox($context, $fromPage) {

        $repository = $context->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
        $actualities = $repository->findBy(array(
            'page' => $fromPage), array('rank' => 'ASC'));
    
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

    public function reviewsBox($context, $fromPage) {

        $repository = $context->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
        $actualities = $repository->findBy(array(
            'isReview' => true), array('rank' => 'ASC'));

        $template = $context->createTemplate();
        $template->setFile('../app/templates/news-module/News/boxReviews.latte');
        $template->actualities = $actualities;

        return $template;
    }
}
