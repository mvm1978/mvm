<?php

namespace App\Models\Library\Reports;

class BookReportModel extends TableReportModel
{
    protected $title = 'Books';
    protected $centered = [
        'length' => TRUE,
        'uploaded_on' => TRUE,
        'rating' => TRUE,
    ];
    protected $orientation = 'L';

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

    protected function setCustomTextColor($field=NULL, $value=NULL)
    {
        switch ($field) {
            case 'rating':
                if ($value) {
                    // RGB (40, 167, 69)  =  CMY (215, 88, 186) - GREEN
                    // RGB (242, 71, 71)  =  CMY (13, 184, 184) - RED
                    $value < 0 ? $this->SetTextColor(13, 184, 184, 0) :
                            $this->SetTextColor(215, 60, 186, 0);
                }

                break;
            default:
                break;
        }
    }

    /*
    ****************************************************************************
    */

    protected function getCustomValue($field=NULL, $value=NULL)
    {
        $return = $value;

        switch ($field) {
            case 'rating':

                if (! $value) {
                    return 'Not Rated';
                }

                $return = $value < 0 ? ' - ' : NULL;

                $value = abs($value);

                if ($value < pow(10, 3)) {
                    $return .= $value;
                } else if ($value < pow(10, 6)) {
                    $return .= (round(($value / pow(10, 3)) * 10 ) / 10) . ' K';
                } else if ($value < pow(10, 9)) {
                    $return .= (round(($value / pow(10, 6)) * 10 ) / 10) . ' M';
                } else if ($value < pow(10, 12)) {
                    $return .= (round(($value / pow(10, 9)) * 10 ) / 10) . ' B';
                }

                break;
            default:
                break;
        }

        return $return;
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