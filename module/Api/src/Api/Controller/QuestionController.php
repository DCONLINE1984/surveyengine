<?php

/* 
 * The question api
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Controller;
use \Application\Controller\CommonController;

class QuestionController extends CommonController
{
    /**
     * The add action
     * @return \Zend\View\Model\JsonModel
     */
    public function addAction()
    {
        if(!\Api\Service\Encoder\Question::validateParameters($this->_request->getParams())){
            $this->getResponse()->setStatusCode(400);
            return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                        "error" => "Incorrect parameters"));
        }else{
            $model = new \Api\Model\Question();
            $model->setQuestionText($this->params()->fromPost("questionText"))
                  ->setRenderId($this->params()->fromPost("renderId"));
            $model = $this->getServiceLocator()->get("Api\Service\Question")->insert($model);
            if($model instanceof \Api\Model\Question){
                return new \Zend\View\Model\JsonModel(array("result" => "true",
                                                            "error"  => "",
                                                            "model"  => $model->toArray()));
            }else{
                $this->getResponse()->setStatusCode(417);
                return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                            "error"  => "Add failed"));
            }
        }
    }
    
    /**
     * The update action
     * @return \Zend\View\Model\JsonModel
     */
    public function updateAction()
    {
        if(!\Api\Service\Encoder\Survey::validateParameters($this->_request->getParams())){
            $this->getResponse()->setStatusCode(400);
            return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                        "error"  => "Incorrect parameters"));
        }else{
            $model = $this->getServiceLocator()->get("Api\Service\Question")->fetchById($id);
            $model->setQuestionText($this->params()->fromPost("questionText"))
                  ->setRenderId($this->params()->fromPost("renderId"));
            $model = $this->getServiceLocator()->get("Api\Service\Question")->update($model);
            if($model instanceof \Api\Model\Question){
                return new \Zend\View\Model\JsonModel(array("result" => "true",
                                                            "error"  => "",
                                                            "model"  => $model->toArray()));
            }else{
                $this->getResponse()->setStatusCode(417);
                return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                            "error"  => "Update failed"));
            }
        }
    }
    
    /**
     * The delete action
     * @return \Zend\View\Model\JsonModel
     */
    public function deleteAction()
    {
        $id = $this->params()->fromPost('questionId', null);
        if(is_null($id)){
            $this->getResponse()->setStatusCode(400);
            return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                        "error"  => "Incorrect parameters"));
        }
        $affectedRows = 0;
        $result = $this->getServiceLocator()->get("Api\Service\Question")    ->delete($id);
        $affectedRows += $result->getAffectedRows();
        $result = $this->getServiceLocator()->get("Api\Service\PageQuestion")->delete(array('question_id' => $id));
        $affectedRows += $result->getAffectedRows();
        $result = $this->getServiceLocator()->get("Api\Service\Answer")      ->delete(array('question_id' => $id));
        $affectedRows += $result->getAffectedRows();
        return new \Zend\View\Model\JsonModel(array("result"   => "true",
                                                    "error"    => "",
                                                    "affected" => $affectedRows));
    }
    
    /**
     * The read action
     * @return \Zend\View\Model\JsonModel
     */
    public function readAction()
    {
        $id     = $this->params()->fromPost('questionId', null);
        if(!is_null($id)){
            //filter by specific id
            $results = $this->getServiceLocator()->get("Api\Service\Question")->fetchById(array('id' => $id));
            if(!empty($results)){
                $finalDatasource = array();
                $results = $results->toArray();
                //now we need to attach any answers
                $answers = $this->getServiceLocator()->get("Api\Service\Answer")->fetchAll(array('question_id' => $id));
                $results['answers'] = \Api\Service\Encoder\Question::toArray($answers);
                $finalDatasource[] = $results;
                $results = $finalDatasource;
            }else{
                $results = array();
            }
        }else{
            $results = $this->getServiceLocator()->get("Api\Service\Question")->fetchAll();
            $results = \Api\Service\Encoder\Question::toArray($results);
        }
        return new \Zend\View\Model\JsonModel(array("result" => "true",
                                                    "error"  => "",
                                                    "collection" => $results));
    }
}