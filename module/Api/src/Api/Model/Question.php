<?php

/* 
 * The question model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Question extends \Application\Model\CommonModel
{
    /**
     * The question text
     * @var string
     */
    protected $questionText;
    
    /**
     * Holds the render id
     * @var int
     */
    protected $renderId;
    
    /**
     * Set the question text
     * @param  int  $id
     * @return \Api\Model\Question
     */
    public function setQuestionText($text)
    {
        $this->questionText = $text;
        return $this;
    }
    
    /**
     * Get the question text
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }
    
    /**
     * Set the render id
     * @param int $id
     * @return \Api\Model\Question
     */
    public function setRenderId($id)
    {
        $this->renderId = $id;
        return $this;
    }
    
    /**
     * Get the render id
     * @return int
     */
    public function getRenderId()
    {
        return $this->renderId;
    }
}