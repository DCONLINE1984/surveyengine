<?php

/* 
 * The survey controller
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Application\Controller;
use \Application\Controller\CommonController;

class SurveyController extends CommonController
{
    /**
     * The index action
     * @return \Zend\View\Model\ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        $id = $this->params()->fromRoute("id", 1);
        if(is_null($id)){
            throw new \Exception("No id found");
            die();
        }
        return new \Zend\View\Model\ViewModel(array('id' => $id));
    }
    
    /**
     * List all surveys
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $surveys = $this->getServiceLocator()->get("Api\Service\Survey")->fetchAll(array());
        return new \Zend\View\Model\ViewModel(array('surveys' => $surveys));
    }
    
    /**
     * Add a new survey
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        return new \Zend\View\Model\ViewModel(array());
    }
    
    /**
     * Edit a survey
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute("id");
        $model = $this->getServiceLocator()->get("Api\Service\Survey")->fetchById($id);
        $expiresDate = new \DateTime($model->getExpiresDate());
        $expiresDate = $expiresDate->format("m/d/Y"); //format the date for what we need
        return new \Zend\View\Model\ViewModel(array('name'        => $model->getName(),
                                                    'expiresDate' => $expiresDate,
                                                    'id'          => $model->getId()));
    }
    
    /**
     * Save the survey
     * @return \Zend\View\Model\ViewModel
     */
    public function saveAction()
    {
        $post       = $this->params()->fromPost();
        $service    = $this->getServiceLocator()->get("Api\Service\Survey");
        $result     = $service->save($post);
        return $this->redirect()->toUrl("/surveyengine/survey/".$result['id']);
    }
    
    /**
     * Edit the survey
     * @return \Zend\View\Model\ViewModel
     */
    public function saveEditAction()
    {
        $post       = $this->params()->fromPost();
        $service    = $this->getServiceLocator()->get("Api\Service\Survey");
        $result     = $service->edit($post);
        return $this->redirect()->toUrl("/surveyengine/survey/list");
    }
    
    /**
     * Delete a survey
     * @return void
     */
    public function deleteAction()
    {
        $id     = $this->params()->fromRoute("id");
        $result = $this->getServiceLocator()->get("Api\Service\Page")->delete(array('survey_id' => $id));
        $result = $this->getServiceLocator()->get("Api\Service\Survey")->delete($id);
        return $this->redirect()->toUrl("/surveyengine/survey/list");
    }
}