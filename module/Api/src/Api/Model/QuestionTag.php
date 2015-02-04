<?php

/* 
 * The question tag model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class QuestionTag extends \Application\Model\CommonModel
{
    /**
     * The question id
     * @var int
     */
    protected $questionId;
    
    /**
     * Holds the tag id
     * @var int
     */
    protected $tagId;
    
    /**
     * Holds the value
     * @var string
     */
    protected $value;
    
    /**
     * Set the question id
     * @param  int  $id
     * @return \Api\Model\QuestionTag
     */
    public function setQuestionId($id)
    {
        $this->questionId = $id;
        return $this;
    }
    
    /**
     * Get the question id
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }
    
    /**
     * Set the tag id
     * @param int $id
     * @return \Api\Model\QuestionTag
     */
    public function setTagId($id)
    {
        $this->tagId = $id;
        return $this;
    }
    
    /**
     * Get the tag id
     * @return int
     */
    public function getTagId()
    {
        return $this->tagId;
    }
    
    /**
     * Set the value
     * @param string $val
     * @return \Api\Model\QuestionTag
     */
    public function setValue($val)
    {
        $this->value = $val;
        return $this;
    }
    
    /**
     * Get the value
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}