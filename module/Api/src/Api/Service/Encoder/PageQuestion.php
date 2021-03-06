<?php

/* 
 * The page question encoder
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service\Encoder;
use \Application\Service\Encoder\CommonEncoder;

class PageQuestion extends CommonEncoder
{
    /**
     * Holds the db fields and their mapping
     * @var string
     */
    public $fields = array('id'             => 'id',
                           'pageId'         => 'page_id',
                           'questionId'     => 'question_id',
                           'sortOrder'      => 'sort_order');

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'page_question';
    }
}