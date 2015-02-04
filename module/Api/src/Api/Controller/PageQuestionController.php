<?php

/* 
 * The page question services
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Controller;
use \Application\Controller\CommonController;

class PageQuestionController extends CommonController
{
    /**
     * The add action
     * @return \Zend\View\Model\JsonModel
     */
    public function addAction()
    {
        if(!\Api\Service\Encoder\PageQuestion::validateParameters($this->_request->getParams())){
            $this->getResponse()->setStatusCode(400);
            return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                        "error" => "Incorrect parameters"));
        }else{
            $model = new \Api\Model\PageQuestion();
            $model->setQuestionText($this->params()->fromPost("questionText"))
                  ->setRenderId($this->params()->fromPost("renderId"));
            $model = $this->getServiceLocator()->get("Api\Service\PageQuestion")->insert($model);
            if($model instanceof \Api\Model\PageQuestion){
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
        if(!\Api\Service\Encoder\PageQuestion::validateParameters($this->_request->getParams())){
            $this->getResponse()->setStatusCode(400);
            return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                        "error"  => "Incorrect parameters"));
        }else{
            $model = $this->getServiceLocator()->get("Api\Service\PageQuestion")->fetchById($id);
            $model->setQuestionText($this->params()->fromPost("questionText"))
                  ->setRenderId($this->params()->fromPost("renderId"));
            $model = $this->getServiceLocator()->get("Api\Service\PageQuestion")->update($model);
            if($model instanceof \Api\Model\PageQuestion){
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
        $id = $this->params()->fromPost('pageQuestionId', null);
        if(is_null($id)){
            $this->getResponse()->setStatusCode(400);
            return new \Zend\View\Model\JsonModel(array("result" => "false",
                                                        "error"  => "Incorrect parameters"));
        }
        $result = $this->getServiceLocator()->get("Api\Service\PageQuestion")->delete($id);
        return new \Zend\View\Model\JsonModel(array("result"   => "true",
                                                    "error"    => "",
                                                    "affected" => $result->getAffectedRows()));
    }
    
    /**
     * The read action
     * @return \Zend\View\Model\JsonModel
     */
    public function readAction()
    {
        $id = $this->params()->fromPost('pageQuestionId', null);
        if(!is_null($id)){
            //filter by specific id
            $results = $this->getServiceLocator()->get("Api\Service\PageQuestion")->fetchAll(array('id' => $id));
        }else{
            $results = $this->getServiceLocator()->get("Api\Service\PageQuestion")->fetchAll();
        }
        return new \Zend\View\Model\JsonModel(array("result" => "true",
                                                    "error"  => "",
                                                    "collection" => \Api\Service\Encoder\PageQuestion::toJson($results)));
    }
}