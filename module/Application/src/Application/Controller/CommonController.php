<?php

/* 
 * The controller abstract. The base for all controller files
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

abstract class CommonController extends AbstractActionController
{
    /**
     * Holds the possible accept criterias for output
     * @var array
     */
    protected $acceptCriteria = array(
      'Zend\View\Model\JsonModel' => array(
         'application/json',
      ),
      'Zend\View\Model\ViewModel' => array(
         'text/html',
      ),
   );
    
    /**
     * The index action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return $this->acceptableViewModelSelector($this->acceptCriteria);
    }
    
    /**
     * The add action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        return $this->acceptableViewModelSelector($this->acceptCriteria);
    }
    
    /**
     * The update action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function update()
    {
        return $this->acceptableViewModelSelector($this->acceptCriteria);
    }
    
    /**
     * The delete action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function delete()
    {
        return $this->acceptableViewModelSelector($this->acceptCriteria);
    }
    
    /**
     * The read action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function read()
    {
        return $this->acceptableViewModelSelector($this->acceptCriteria);
    }
}