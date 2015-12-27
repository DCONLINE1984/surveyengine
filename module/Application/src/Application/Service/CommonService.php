<?php

/* 
 * The common service: all services extend from this one
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;

abstract class CommonService implements ServiceLocatorAwareInterface
{
    /**
     * Holds the service manager
     * @var \Zend\ServiceManager
     */
    protected $sm;
    
    /**
     * Holds a db adapter
     * @var \Zend\Db\Adapter
     */
    protected $db;
    
    /**
     * Holds the db table
     * @var string
     */
    protected $table;
    
    /**
     * Holds the primary key
     * @var string
     */
    protected $primaryKey = "id";
    
    /**
     * Holds the encoder
     * @var obj
     */
    protected $encoder;
    
    /**
     * Constructor
     */
    public function __construct(){}
    
    /**
     * Set the db adapter
     * @param \Zend\Db\Adapter $dbAdapter
     */
    public function setDb($dbAdapter)
    {
        $this->db = new Sql($dbAdapter);
    }
    
    /**
     * Get the database adapter
     * @return \Zend\Db\Adapter
     */
    public function getDb()
    {
        return $this->db;
    }
    
    /**
     * Set the service locator
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;
    }    

    /**
     * Get the service locator
     * @return \Zend\ServiceManager
     */
    public function getServiceLocator()
    {
        return $this->sm;
    }

    /**
     * Set the model (used in the encoder later)
     * @param object $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
    
    /**
     * Get the model
     * @return object
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Set the primary key
     * @param string $key
     * @return void
     */
    public function setPrimaryKey($key="id")
    {
        $this->primaryKey = $key;
    }
    
    /**
     * Get the primary key
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    
    /**
     * Insert a new record
     * @param  object $params
     * @return object
     */
    public function insert($obj)
    {
        $insert = $this->getDb()->insert($this->table);
        $values = $obj->toArray();
        $values = $this->getEncoder()->map($values);
        unset($values[$this->primaryKey]);
        $insert->values($values);
        $statement = $this->getDb()->prepareStatementForSqlObject($insert);
        $results = $statement->execute();
        $obj->setId($results->getGeneratedValue());
        return $obj;
    }
    
    /**
     * Insert a record not using an object
     * @param  array $values
     * @return int
     */
    public function insertNoObject($values)
    {
        $insert = $this->getDb()->insert($this->table);
        unset($values[$this->primaryKey]);
        $insert->values($values);
        $statement = $this->getDb()->prepareStatementForSqlObject($insert);
        $result = $statement->execute();
        return $result->getGeneratedValue();
    }
    
    /**
     * Update a record
     * @param  object $params
     * @return object
     */
    public function update($obj)
    {
        $insert = $this->getDb()->update($this->table);
        $values = $obj->toArray();
        $values = $this->getEncoder()->map($values);
        $insert->where(array($this->primaryKey => $values[$this->primaryKey]));
        unset($values[$this->primaryKey]);
        $insert->set($values);
        $statement = $this->getDb()->prepareStatementForSqlObject($insert);
        $results = $statement->execute();
        return $obj;
    }
    
    /**
     * Fetch all results
     * @param  array    $where          The where parameters
     * @param  string   $order          The ordering for the statement
     * @param  bool     $ignoreEncode   Ignore the model encoding
     * @return array
     */
    public function fetchAll($where=array(),
                             $order=null,
                             $ignoreEncode=false)
    {  
        $select = $this->getDb()->select($this->table);
        if(!is_null($where) && !empty($where)){
            $select->where($where);
        }
        if(!is_null($order)){
            $select->order($order);
        }else{
            $select->order($this->getPrimaryKey());
        }
        $statement = $this->getDb()->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        if(is_object($this->getEncoder()) && !$ignoreEncode){
            return $this->getEncoder()->encode($results, 
                                               $this->model);
        }else{
            $datasource = array();
            foreach($results as $row){
                $datasource[] = $row;
            }
            return $datasource;
        }
        return $results;
    }
    
    /**
     * Fetch by id
     * @param  int $id
     * @return object
     */
    public function fetchById($id)
    {
        $result = $this->fetchAll(array($this->primaryKey => $id));
        return $result[0];
    }
    
    /**
     * Delete a record
     * @param  int $id
     * @return int
     */
    public function delete($id)
    {
        $obj = $this->getDb()->delete($this->table);
        if(is_array($id)){
            $obj->where($id);
        }else{
            $obj->where(array($this->primaryKey => $id));
        }
        $statement = $this->getDb()->prepareStatementForSqlObject($obj);
        $results = $statement->execute();
        return $results;
    }
    
    /**
     * Set the encoder
     * @param object $encoder
     */
    public function setEncoder($encoder)
    {
        $this->encoder = $encoder;
    }
    
    /**
     * Get the encoder
     * @return object
     */
    public function getEncoder()
    {
        return $this->encoder;
    }
}