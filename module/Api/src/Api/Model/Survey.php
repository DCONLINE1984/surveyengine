<?php

/* 
 * The survey model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Survey extends \Application\Model\CommonModel
{
    /**
     * The name of the survey
     * @var string
     */
    protected $name;
    
    /**
     * Holds the expires date
     * @var string
     */
    protected $expiresDate;
    
    /**
     * Set the survey name
     * @param  string $name
     * @return \Api\Model\Survey
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get the survey name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the expires date
     * @param  string $date
     * @return \Api\Model\Survey
     */
    public function setExpiresDate($date)
    {
        $date = new \DateTime($date);
        $date = $date->format('Y-m-d H:i:s'); //format for mysql
        $this->expiresDate = $date;
        return $this;
    }
    
    /**
     * Get the expires date
     * @return string
     */
    public function getExpiresDate()
    {
        return $this->expiresDate;
    }
}