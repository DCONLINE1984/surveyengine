<?php

/* 
 * The response model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Response extends \Application\Model\CommonModel
{
    /**
     * The answer id
     * @var string
     */
    protected $answerId;
    
    /**
     * Holds the question id
     * @var string
     */
    protected $questionId;
    
    /**
     * Holds the answer value
     * @var string
     */
    protected $value;
    
    /**
     * Set the answer id
     * @param  int  $id
     * @return \Api\Model\Response
     */
    public function setAnswerId($id)
    {
        $this->answerId = $id;
        return $this;
    }
    
    /**
     * Get the answer id
     * @return int
     */
    public function getAnswerId()
    {
        return $this->answerId;
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
    
    /**
     * Set the response value
     * @param string $val
     * @return \Api\Model\Response
     */
    public function setValue($val)
    {
        $this->value = $val;
        return $this;
    }
    
    /**
     * Get the response value
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}