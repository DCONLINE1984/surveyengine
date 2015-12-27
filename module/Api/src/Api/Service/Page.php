<?php

/* 
 * The page service
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service;
use \Application\Service\CommonService;

class Page extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'page';
    }
    
    /**
     * Get the elements on a page
     * @param  int $pageId
     * @return array
     */
    public function getElements($pageId)
    {
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $questionService     = $this->getServiceLocator()->get("Api\Service\Question");
        $answerService       = $this->getServiceLocator()->get("Api\Service\Answer");
        $questions = $pageQuestionService->fetchAll(array('page_id' => $pageId));
        $questionCollection = array();
        foreach($questions as $q){
            $questionModel = $questionService->fetchById($q->getQuestionId());
            //now get any answers
            $answers       = $answerService->fetchAll(array('question_id' => $questionModel->getId()));
            $questionModel = $questionModel->toArray();
            $questionModel['answers'] = \Api\Service\Encoder\Answer::toArray($answers);
            if($questionModel['renderId']!=5){
                $questionCollection[$questionModel['id']]['questions'][]    = $questionModel;
                $questionCollection[$questionModel['id']]['questionText']   = $questionModel['questionText'];
                $questionCollection[$questionModel['id']]['renderId']       = $questionModel['renderId'];
                $questionCollection[$questionModel['id']]['id']             = $questionModel['id'];
            }else{
                if(isset($questionCollection[$questionModel['parentId']])){
                    $questionCollection[$questionModel['parentId']]['questions'][] = $questionModel;
                }else{
                    $questionCollection[$questionModel['id']]['questions'][]    = $questionModel;
                    $questionCollection[$questionModel['id']]['questionText']   = $questionModel['header'];
                    $questionCollection[$questionModel['id']]['renderId']       = $questionModel['renderId'];
                    $questionCollection[$questionModel['id']]['id']             = $questionModel['id'];
                    $questionCollection[$questionModel['id']]['columns']        = $questionModel['answers'];
                }
            }
        }
        $questionCollection = array_values($questionCollection);
        return $questionCollection;
    }
}