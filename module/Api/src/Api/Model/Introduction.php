<?php

/* 
 * The introduction model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Introduction extends \Application\Model\CommonModel
{
    /**
     * The introduction text
     * @var string
     */
    protected $text;
    
    /**
     * Holds the survey id
     * @var int
     */
    protected $surveyId;
    
    /**
     * Set the introduction text
     * @param  string $text
     * @return \Api\Model\Introduction
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
    
    /**
     * Get the text
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set the survey id
     * @param int $id
     * @return \Api\Model\Introduction
     */
    public function setSurveyId($id)
    {
        $this->surveyId = $id;
        return $this;
    }
    
    /**
     * Get the survey id
     * @return int
     */
    public function getSurveyId()
    {
        return $this->surveyId;
    }
}