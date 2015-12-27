<?php

/* 
 * The page model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Page extends \Application\Model\CommonModel
{
    /**
     * The name of the page
     * @var string
     */
    protected $name;
    
    /**
     * Holds the sort order
     * @var string
     */
    protected $sortOrder;
    
    /**
     * Holds the survey id
     * @var Int
     */
    protected $surveyId;
    
    /**
     * Set the page name
     * @param  string $name
     * @return \Api\Model\Page
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get the page name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the sort order
     * @param  int  $order
     * @return \Api\Model\Page
     */
    public function setSortOrder($order)
    {
        $this->sortOrder = $order;
        return $this;
    }
    
    /**
     * Get the sort order
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
    
    /**
     * Set the survey id
     * @param Int $id
     * @return \Api\Model\Page
     */
    public function setSurveyId($id)
    {
        $this->surveyId = $id;
        return $this;
    }
    
    /**
     * Get the survey id
     * @return Int
     */
    public function getSurveyId()
    {
        return $this->surveyId;
    }
}