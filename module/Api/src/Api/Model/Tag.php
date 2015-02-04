<?php

/* 
 * The tag model
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Model;

class Tag extends \Application\Model\CommonModel
{
    /**
     * The tag name
     * @var string
     */
    protected $name;
    
    /**
     * Set the tag name
     * @param  string $name
     * @return \Api\Model\Tag
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get the name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}