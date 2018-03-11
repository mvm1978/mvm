<?php

namespace App\Models\Library\Reports;

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
    }

    /*
    ****************************************************************************
    */

}