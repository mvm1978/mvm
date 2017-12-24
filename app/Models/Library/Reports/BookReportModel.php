<?php

namespace App\Models\Library\Reports;

use App\Models\BaseModel;

class BookReportModel extends TableReportModel
{
    protected $title = 'Books';
    protected $centered = [
        'length' => TRUE,
        'uploaded_on' => TRUE,
        'upvotes' => TRUE,
        'downvotes' => TRUE,
    ];
    protected $orientation = 'L';

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

    protected function setCustomTextColor($field=NULL)
    {
        switch ($field) {
            case 'upvotes':
                // RGB (40, 167, 69)  =  CMY (215, 88, 186)
                $this->SetTextColor(215, 60, 186, 0);
                break;
            case 'downvotes':
                // RGB (242, 71, 71)  =  CMY (13, 184, 184)
                $this->SetTextColor(13, 184, 184, 0);
                break;
            default:
                break;
        }
    }

    /*
    ****************************************************************************
    */

}