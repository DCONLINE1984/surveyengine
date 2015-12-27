<?php

/* 
 * The survey service
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service;
use \Application\Service\CommonService;

class Survey extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'survey';
    }
    
    /**
     * Save the new survey
     * @param Array $post
     * @return Array
     */
    public function save($post)
    {
        $model = new \Api\Model\Survey($post); //hydrate from post
        $model->setExpiresDate($post['expiresDate']);
        $model = $this->insert($model);
        if(empty($model->getId())){
            return array('result' => false,
                         'id'     => 0);
        }
        $surveyId = $model->getId();
        //now add a page
        $model = new \Api\Model\Page();
        $model->setName("Page 1")
              ->setSortOrder(1)
              ->setSurveyId($surveyId);
        $result = $this->getServiceLocator()->get("Api\Service\Page")->insert($model);
        return array('result' => true,
                     'id'     => $surveyId);
    }
    
    /**
     * Edit a survey
     * @param Array $post
     * @return Object
     */
    public function edit($post)
    {
        $model = $this->fetchById($post['uid']);
        $model->hydrate($post);
        $model->setExpiresDate($post['expiresDate']);
        $result = $this->update($model);
        return $result;
    }
}