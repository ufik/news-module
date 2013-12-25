<?php

namespace AdminModule\NewsModule;

/**
 * Description of NewsPresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class NewsPresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	private $actuality;
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){
	}
	
	protected function createComponentNewsGrid($name){
		
		$grid = $this->createGrid($this, $name, 'WebCMS\NewsModule\Doctrine\Actuality', array(array('by' => 'date', 'dir' => 'DESC')), NULL);
		
		$grid->addColumnText('title', 'Name')->setSortable()->setFilterText();
		$grid->addColumnDate('date', 'Date')->setSortable();
		
		$grid->addActionHref("updateActuality", 'Edit', 'updateActuality', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn' , 'btn-primary', 'ajax')));
		$grid->addActionHref("deleteActuality", 'Delete', 'deleteActuality', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn', 'btn-danger'), 'data-confirm' => 'Are you sure you want to delete this item?'));

		return $grid;
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->idPage = $idPage;
	}
	
	public function actionUpdateActuality($idPage, $id){
		$this->reloadContent();
		
		if(is_numeric($id)){
			$this->actuality = $this->repository->find($id);
		}else{
			$this->actuality = new \WebCMS\NewsModule\Doctrine\Actuality;
		}
	}
	
	public function actionDeleteActuality($id){

		$act = $this->repository->find($id);
		$this->em->remove($act);
		$this->em->flush();
		
		$this->flashMessage('Actuality has been removed.', 'success');
		
		if(!$this->isAjax()){
			$this->redirect('default', array(
				'idPage' => $this->actualPage->getId()
			));
		}
	}
	
	public function createComponentActualityForm(){
		$form = $this->createForm();
		
		$form->addText('title', 'Title')->setRequired('Fill in title.');
		$form->addText('date', 'Date')->setAttribute('class', array('datepicker'))->setRequired('Fill in date of this actuality.');
		$form->addTextArea('perex', 'Perex')->setAttribute('class', array('editor'));
		$form->addTextArea('text', 'Text')->setAttribute('class', array('editor'));
		
		$form->addSubmit('send', 'Save')->setAttribute('class', array('btn btn-success'));
		$form->onSuccess[] = callback($this, 'actualityFormSubmitted');

		$form->setDefaults($this->actuality->toArray());
		
		return $form;
	}
	
	public function actualityFormSubmitted($form){
		$values = $form->getValues();
		
		$this->actuality->setTitle($values->title);
		$this->actuality->setPerex($values->perex);
		$this->actuality->setText($values->text);
		$this->actuality->setDate(new \Nette\DateTime($values->date));
		$this->actuality->setPage($this->actualPage);
		
		if(!$this->actuality->getId()){
			$this->em->persist($this->actuality);
		}else{
			// delete old photos and save new ones
			$qb = $this->em->createQueryBuilder();
			$qb->delete('WebCMS\NewsModule\Doctrine\Photo', 'l')
					->where('l.actuality = ?1')
					->setParameter(1, $this->actuality)
					->getQuery()
					->execute();
		}
			
		if(array_key_exists('files', $_POST)){
			$counter = 0;
			if(array_key_exists('fileDefault', $_POST)) $default = intval($_POST['fileDefault'][0]) - 1;
			else $default = -1;
			
			foreach($_POST['files'] as $path){

				$photo = new \WebCMS\NewsModule\Doctrine\Photo;
				$photo->setTitle($_POST['fileNames'][$counter]);
				
				if($default === $counter){
					$photo->setDefault(TRUE);
				}else{
					$photo->setDefault(FALSE);
				}
				
				$photo->setPath($path);
				$photo->setActuality($this->actuality);

				$this->em->persist($photo);

				$counter++;
			}
		}
		
		$this->em->flush();
		
		$this->flashMessage('Actuality has been saved.', 'success');
		$this->redirect('default', array(
			'idPage' => $this->actualPage->getId()
		));
	}
	
	public function renderUpdateActuality($idPage){
		
		$this->template->actuality = $this->actuality;
		$this->template->idPage = $idPage;
	}
}