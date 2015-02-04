<?php

/* 
 * The answer model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Answer extends \Application\Model\CommonModel
{
    /**
     * The answer text
     * @var string
     */
    protected $answerText;
    
    /**
     * Holds the question id
     * @var string
     */
    protected $questionId;
    
    /**
     * Set the answer text
     * @param  string $text
     * @return \Api\Model\Answer
     */
    public function setAnswerText($text)
    {
        $this->answerText = $text;
        return $this;
    }
    
    /**
     * Get the answer text
     * @return string
     */
    public function getAnswerText()
    {
        return $this->answerText;
    }

    /**
     * Set the question id
     * @param  int  $id
     * @return \Api\Model\Answer
     */
    public function setQuestionId($id)
    {
        $this->questionId = $id;
        return $this;
    }
    
    /**
     * Get the question id
     * @return string
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }
}