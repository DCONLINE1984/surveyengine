<?php

/* 
 * The common model: all models extend from this one
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Model;

class CommonModel
{
    /**
     * Holds the uid
     * @var int
     */
    protected $id = 0;
    
    /**
     * The base constructor
     * Optionally, hydrates if possible
     * @param string/array $obj     Contains the info to hydrate the model
     */
    public function __construct($obj="")
    {
        if(is_array($obj)){
            $this->hydrate($obj);
        }
    }
    
    /**
     * Hydrate the model
     * @param type $obj
     * @return void
     */
    public function hydrate($obj)
    {
        foreach($obj as $key=>$value){
            if(property_exists($this, $key) && !empty($value)){
                $this->$key = $value;
            }
        }
        return $this;
    }
    
    /**
     * Set the uid
     * @param  int $id
     * @return \Application\Model\CommonModel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get the uid
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Object to array
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}