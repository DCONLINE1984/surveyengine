<?php

/* 
 * The question controller
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Application\Controller;
use \Application\Controller\CommonController;

class QuestionController extends CommonController
{
    /**
     * The add action
     * @return \Zend\View\Model\JsonModel
     */
    public function addAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $service = $this->getServiceLocator()->get("Api\Service\Question");
            $result = $service->addFullQuestion($this->getRequest()->getPost());
            if(!$result){
                return new \Zend\View\Model\JsonModel(array('result' => 'false',
                                                            'error'  => 'Failed to add question'));
            }
        }
        $result['result'] = true;
        return new \Zend\View\Model\JsonModel($result);
    }
}