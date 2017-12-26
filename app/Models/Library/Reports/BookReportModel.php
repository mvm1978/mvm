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

    public function createPDF($title, $author, $file)
    {
        $this->AddPage('P');

        $pageWidth = $this->getCustomPageWidth();

        $this->customMultiCell([
            'width' => $pageWidth,
            'text' => $title,
            'align' => 'C',
        ]);

        $this->customMultiCell([
            'width' => $pageWidth,
            'text' => 'by ' . $author,
            'align' => 'C',
        ]);

        $this->Ln();

        $text = '     Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed ' .
                'do eiusmod tempor incididunt ut labore et dolore magna aliqua. ' .
                'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris ' .
                'nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor ' .
                'in reprehenderit in voluptate velit esse cillum dolore eu fugiat ' .
                'nulla pariatur. Excepteur sint occaecat cupidatat non proident, ' .
                'sunt in culpa qui officia deserunt mollit anim id est laborum.';

        for ($count=0; $count<3; $count++) {
            $this->customMultiCell([
                'width' => $pageWidth,
                'text' => $text,
                'align' => 'L',
            ]);
        }

        $this->output($file, 'F');
    }

    /*
    ****************************************************************************
    */

}