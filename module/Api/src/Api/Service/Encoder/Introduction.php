<?php

/* 
 * The introduction encoder
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service\Encoder;
use \Application\Service\Encoder\CommonEncoder;

class Introduction extends CommonEncoder
{
    /**
     * Holds the db fields and their mapping
     * @var string
     */
    public $fields = array('id'             => 'id',
                           'text'           => 'text',
                           'surveyId'       => 'survey_id');

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'introduction';
    }
}