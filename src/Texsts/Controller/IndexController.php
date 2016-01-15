<?php

namespace Texsts\Controller;

use Application\Lib\YeopenController;
use Texsts\Service\Text;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;
use Texsts\Entity\Text as TextEntity;
use \Texsts\Form\Text as TextForm;


class IndexController extends YeopenController
{

    protected $textsService;
    protected $textForm;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $currentUser = $this->getCurrentUser();
        $inputData = $this->params()->fromPost();

        $service = $this->getTextsService();

        $entity = new TextEntity();
        $form = $this->getTextForm();


        $form->setHydrator(new ClassMethods());
        $form->bind($entity);
        $form->setData($inputData);

        if ($form->isValid())
        {
            $entity->setUser($currentUser);
            $service->create($entity);
            $result = $entity::createDump($entity);
            $result['success'] = true;
        } else {
            $result['success'] = false;
            //var_dump($form->getMessages());
        }


        return $this->getJsonModel($result);
    }

    //TODO Extract from here
    public function tryToBindData($inputData, $entity, $form)
    {
        $form->setHydrator(new ClassMethods());
        $form->bind($entity);
        $form->setData($inputData);

        if ($form->isValid())
        {
            return true;
        } else {
            return false;
        }
    }

    public function editAction()
    {
        $currentUser = $this->getCurrentUser();
        $inputData = $this->params()->fromPost();
        $service = $this->getTextsService();


        $entity = $service->getById($inputData['id']);
        $form = $this->getTextForm();


        $result = [];
        if($this->tryToBindData($inputData, $entity, $form)){
            //$result = $entity::createDump($entity);
            $service->editTextByUser($entity, $currentUser);
            $result['success'] = true;
        } else {
            $result['success'] = false;
        }


        return $this->getJsonModel($result);

    }

    public function getWithId()
    {
        /*
        $currentUser = $this->getCurrentUser();
        $inputData = $this->params()->fromPost();
        $service = $this->getTextsService();

        $entity = $service->getById($inputData['id']);


        return $this->getJsonModel([$entity::createDump($entity)]);
        */
    }

    public function deleteAction()
    {
        $currentUser = $this->getCurrentUser();
        $inputData = $this->params()->fromPost();
        $service = $this->getTextsService();

        $entity = $service->getById($inputData['id']);


        $result = false;
        if($entity){
            if ($service->deleteTextByUser($entity, $currentUser)){
                $result = true;
            }
        }

        return $this->getJsonModel(['success' => $result]);

    }



    /**
     * @return Text
     */
    public function getTextsService()
    {
        if(!$this->textsService){
            $this->textsService = $this->getServiceLocator()->get('Texsts\Service\Text');
        }
        return $this->textsService;
    }

    public function setTextService(Text $textService)
    {
        $this->textsService = $textService;
    }

    /**
     * @return TextForm
     */
    public function getTextForm()
    {
        if(!$this->textForm){
            $this->textForm = $this->getServiceLocator()->get('Texsts\Form\Text');
        }

        return $this->textForm;
    }

    public function setTextForm(TextForm $textForm)
    {
        $this->textForm = $textForm;
    }



}

