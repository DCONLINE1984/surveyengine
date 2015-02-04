<?php

/* 
 * The page question model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class PageQuestion extends \Application\Model\CommonModel
{
    /**
     * The question id
     * @var int
     */
    protected $questionId;
    
    /**
     * Holds the page id
     * @var int
     */
    protected $pageId;
    
    /**
     * Holds the sort order
     * @var int
     */
    protected $sortOrder;
    
    /**
     * Set the question id
     * @param  int  $id
     * @return \Api\Model\PageQuestion
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
     * Set the page id
     * @param int $id
     * @return \Api\Model\PageQuestion
     */
    public function setPageId($id)
    {
        $this->pageId = $id;
        return $this;
    }
    
    /**
     * Get the page id
     * @return int
     */
    public function getPageId()
    {
        return $this->pageId;
    }
    
    /**
     * Holds the sort order
     * @param int $order
     * @return \Api\Model\PageQuestion
     */
    public function setSortOrder($order=1)
    {
        $this->sortOrder = $order;
        return $this;
    }
    
    /**
     * Get the sort order
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}