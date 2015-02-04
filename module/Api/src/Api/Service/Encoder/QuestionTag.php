<?php

/* 
 * The question tag encoder
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api\Service\Encoder;
use \Application\Service\Encoder\CommonEncoder;

class QuestionTag extends CommonEncoder
{
    /**
     * Holds the db fields and their mapping
     * @var string
     */
    public $fields = array('id'             => 'id',
                           'questionId'     => 'question_id',
                           'tagId'          => 'tag_id',
                           'value'          => 'value');

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'question_tag';
    }
}