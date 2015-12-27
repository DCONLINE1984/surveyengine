<?php

/* 
 * The Page controller
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Application\Controller;
use \Application\Controller\CommonController;

class PageController extends CommonController
{
    /**
     * The index action
     * @return \Zend\View\Model\JsonModel
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $surveyId = $this->getRequest()->getPost("surveyId");
            $callback['questions'] = $this->getServiceLocator()->get("Api\Service\Page")->getElements(2); //TODO: dynamic
            $callback['survey']    = $this->getServiceLocator()->get("Api\Service\Survey")->fetchById($surveyId)->toArray();
            $callback['page']      = $this->getServiceLocator()->get("Api\Service\Page")->fetchById(2)->toArray();
        }
        return new \Zend\View\Model\JsonModel($callback);
    }
}