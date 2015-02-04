<?php

/* 
 * The answer service
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service;
use \Application\Service\CommonService;

class Answer extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'answer';
    }
}