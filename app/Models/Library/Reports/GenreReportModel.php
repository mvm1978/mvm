<?php

namespace App\Models\Library\Reports;

use App\Models\BaseModel;

class GenreReportModel extends TableReportModel
{
    protected $title = 'Genres';
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