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
        switch($parameters['type']){
            case "MULTIPLE":
                    if($parameters['mode']=='add'){
                        return $this->addMultipleChoice($parameters);
                    }else{
                        return $this->editMultipleChoice($parameters);
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
     * Add a new multiple choice
     * @param  array $parameters
     * @return array
     */
    public function addMultipleChoice($parameters)
    {
        $answerService =       $this->getServiceLocator()->get("Api\Service\Answer");
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $surveyId = $parameters['surveyId'];
        $pageId   = $parameters['pageId'];
        $render = 1;
        if(isset($parameters['makeCheckbox'])){
            $render = 2;
        }
        $questionModel = new \Api\Model\Question();
        $questionModel->setQuestionText($parameters['questionText'])
                      ->setRenderId($render);
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
                          ->setPageId($parameters['pageId']);
        $pageQuestionModel = $pageQuestionService->insert($pageQuestionModel);
        return array('question'     => $questionModel->toArray(),
                     'answers'      => \Api\Service\Encoder\Answer::toJson($answerCollection),
                     'pageQuestion' => $pageQuestionModel->toArray());
    }
    
    /**
     * Edit a multiple choice question
     * @param  array $parameters
     * @return array
     */
    public function editMultipleChoice($parameters)
    {
        $answerService =       $this->getServiceLocator()->get("Api\Service\Answer");
        $pageQuestionService = $this->getServiceLocator()->get("Api\Service\PageQuestion");
        $surveyId = $parameters['surveyId'];
        $pageId   = $parameters['pageId'];
        $render = 1;
        if(isset($parameters['makeCheckbox'])){
            $render = 2;
        }
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
                          ->setPageId($parameters['pageId']);
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