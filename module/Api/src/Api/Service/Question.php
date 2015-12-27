<?php

/* 
 * The question service
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service;
use \Application\Service\CommonService;

class Question extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'question';
    }
    
    /**
     * Add a complete question (from post request)
     * @param  array $parameters
     * @return boolean
     */
    public function addFullQuestion($parameters)
    {
        //get the current page number
        $page = $this->getServiceLocator()->get("Api\Service\Page")->fetchAll(array('survey_id' => $parameters['surveyId'],
                                                                                    'sort_order' => $parameters['pageNumber']));
        $parameters['pageId'] = $page[0]->getId(); //get the page id
        switch($parameters['type']){
            case "MULTIPLE":
                $renderId = 1;
                if(isset($parameters['makeCheckbox'])){
                    $renderId = 2;
                }
                if($parameters['mode']=='add'){
                    return $this->addMultipleChoice($parameters, $renderId);
                }else{
                    return $this->editMultipleChoice($parameters, $renderId);
                }
            break;
            case "DROPDOWN":
                //we can just use the same multiple choice code, just need
                //to change the render id to 6
                if($parameters['mode']=='add'){
                    return $this->addMultipleChoice($parameters, 6);
                }else{
                    return $this->editMultipleChoice($parameters, 6);
                }
            break;
            case "RATING":
                //TODO: edit rating
                if($parameters['mode']=='add'){
                    return $this->addRating($parameters);
                }else{
                    return $this->editRating($parameters);
                }
            break;
            case "HTML":
                //TODO: edit HTML
                if($parameters['mode']=='add'){
                    return $this->addHTML($parameters);
                }else{
                    return $this->editHTML($parameters);
                }
            break;
            case "TEXT":
                    if($parameters['mode']=='add'){
                        return $this->addText($parameters);
                    }else{
                        return $this->editText($parameters);
                    }
                break;
        }
        return false;
    }
    
    /**
     * Add static html
     * @param Array $parameters
     * @return Array
     */
    public function addHTML($parameters)
    {
        $parameters['questionText'] = $parameters['content'];
        return $this->addMultipleChoice($parameters, 7);
    }
    
    /**
     * Add a rating question: Also uses the multiple choice code
     * @param  array $parameters
     * @return array
     */
    public function addRating($parameters)
    {
        $collection = array();
        $questionId = 0;
        //loop through each question
        for($i=0;$i<=30;$i++){
            if(isset($parameters['questionChoice'.$i])){
                $parameters['questionText'] = $parameters['questionChoice'.$i];
                $parameters['parentId']     = $questionId;
                $callback     = $this->addMultipleChoice($parameters, 5);
                $questionId   = $callback['question']['id'];
                $collection[] = $callback;
            }
        }
        return $collection;
    }
    
    /**
     * Add a new multiple choice
     * @param  array $parameters
     * @param  int   $render
     * @return array
     */
    public function addMultipleChoice($parameters, $render)
    {
        if(!isset($parameters['parentId'])){
            $parameters['parentId'] = 0;
        }
        if(!isset($parameters['header'])){
            $parameters['header'] = "";
        }
        $answerService =       $this->getServiceLocator()->get("Api\Service\Answer");
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $surveyId = $parameters['surveyId'];
        $pageId   = $parameters['pageId'];
        $questionModel = new \Api\Model\Question();
        $questionModel->setQuestionText($parameters['questionText'])
                      ->setRenderId($render)
                      ->setParentId($parameters['parentId'])
                      ->setHeader($parameters['header']);
        $questionModel = $this->insert($questionModel);
        //now add the answers
        $answerCollection = array();
        for($i=0;$i<50;$i++){
            if(isset($parameters['answerChoice'.$i])){
                $answerModel = new \Api\Model\Answer();
                $answerModel->setAnswerText($parameters['answerChoice'.$i])
                            ->setQuestionId($questionModel->getId());
                $answerModel = $answerService->insert($answerModel);
                $answerCollection[] = $answerModel;
            }
        }
        //now add the page question link
        $pageQuestionModel = new \Api\Model\PageQuestion();
        $pageQuestionModel->setQuestionId($questionModel->getId())
                          ->setPageId($parameters['pageId'])
                          ->setSortOrder($this->getPageQuestionSortOrder($parameters['pageId']));
        $pageQuestionModel = $pageQuestionService->insert($pageQuestionModel);
        return array('question'     => $questionModel->toArray(),
                     'answers'      => \Api\Service\Encoder\Answer::toJson($answerCollection),
                     'pageQuestion' => $pageQuestionModel->toArray());
    }
    
    /**
     * Get the next sort order in line
     * @param Int $pageId
     * @return Int
     */
    public function getPageQuestionSortOrder($pageId)
    {
        $pages = $this->getServiceLocator()->get("Api\Service\PageQuestion")->fetchAll(array('page_id' => $pageId),
                                                                                       'sort_order DESC');
        if(!isset($pages[0]) || empty($pages[0]))
            return 1;
        $page = $pages[0];
        return ($page->getSortOrder() + 1);
    }
    /**
     * Edit a multiple choice question
     * @param  array $parameters
     * @param  int   $render
     * @return array
     */
    public function editMultipleChoice($parameters, $render)
    {
        $answerService =       $this->getServiceLocator()->get("Api\Service\Answer");
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $surveyId = $parameters['surveyId'];
        $pageId   = $parameters['pageId'];
        $questionModel = $this->getServiceLocator()->get("Api\Service\Question")->fetchById($parameters['questionId']);
        $questionModel->setQuestionText($parameters['questionText'])
                      ->setRenderId($render);
        $questionModel = $this->update($questionModel);
        //get the existing answers
        $answerModels = $this->getServiceLocator()->get("Api\Service\Answer")->fetchAll(array('question_id' => $parameters['questionId']));
        //now update the answers
        $answerCollection = array();
        for($i=0;$i<50;$i++){
            if(isset($answerModels[$i])){
                $answerModel = $answerModels[$i];
                if(isset($parameters['answerChoice'.$i])){
                    $answerModel->setAnswerText($parameters['answerChoice'.$i])
                                ->setQuestionId($questionModel->getId());
                    $answerModel = $answerService->update($answerModel);
                }else{
                    //delete the extra
                    $answerService->delete($answerModel->getId());
                }
            }else{
                if(isset($parameters['answerChoice'.$i])){
                    $answerModel = new \Api\Model\Answer();
                    $answerModel->setAnswerText($parameters['answerChoice'.$i])
                                ->setQuestionId($questionModel->getId());
                    $answerModel = $answerService->insert($answerModel);
                }
            }
            $answerCollection[] = $answerModel;
        }
        return array('question'     => $questionModel->toArray(),
                     'answers'      => \Api\Service\Encoder\Answer::toArray($answerCollection));
    }
    
    /**
     * Add a new text question
     * @param  array $parameters
     * @return array
     */
    public function addText($parameters)
    {
        $answerService =       $this->getServiceLocator()->get("Api\Service\Answer");
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $surveyId = $parameters['surveyId'];
        $pageId   = $parameters['pageId'];
        $render = 3;
        if(isset($parameters['makeTextarea'])){
            $render = 4;
        }
        $questionModel = new \Api\Model\Question();
        $questionModel->setQuestionText($parameters['questionText'])
                      ->setRenderId($render);
        $questionModel = $this->insert($questionModel);
        //now add the answer
        $answerModel = new \Api\Model\Answer();
        $answerModel->setAnswerText("text")
                    ->setQuestionId($questionModel->getId());
        $answerModel = $answerService->insert($answerModel);
        //now add the page question link
        $pageQuestionModel = new \Api\Model\PageQuestion();
        $pageQuestionModel->setQuestionId($questionModel->getId())
                          ->setPageId($parameters['pageId'])
                          ->setSortOrder($this->getPageQuestionSortOrder($parameters['pageId']));
        $pageQuestionModel = $pageQuestionService->insert($pageQuestionModel);
        return array('question'     => $questionModel->toArray(),
                     'answers'      => array($answerModel->toArray()),
                     'pageQuestion' => $pageQuestionModel->toArray());
    }
    
    /**
     * Edit the text question
     * @param  array $parameters
     * @return array
     */
    public function editText($parameters)
    {
        $answerService =       $this->getServiceLocator()->get("Api\Service\Answer");
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $surveyId = $parameters['surveyId'];
        $pageId   = $parameters['pageId'];
        $render = 3;
        if(isset($parameters['makeTextarea'])){
            $render = 4;
        }
        $questionModel = $this->getServiceLocator()->get("Api\Service\Question")->fetchById($parameters['questionId']);
        $questionModel->setQuestionText($parameters['questionText'])
                      ->setRenderId($render);
        $questionModel = $this->update($questionModel);
        return array('question'     => $questionModel->toArray());
    }
}