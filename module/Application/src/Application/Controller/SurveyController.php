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
}