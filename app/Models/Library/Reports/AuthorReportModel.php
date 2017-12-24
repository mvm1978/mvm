<?php

namespace App\Models\Library\Reports;

use App\Models\BaseModel;

class AuthorReportModel extends TableReportModel
{
    protected $title = 'Authors';
    protected $orientation = 'P';

    /*
    ****************************************************************************
    */

    public function __construct()
    {
        parent::__construct($this->orientation);

        $this->baseModel = new BaseModel();
    }

    /*
    ****************************************************************************
    */

}