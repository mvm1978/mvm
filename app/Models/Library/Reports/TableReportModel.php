<?php

namespace App\Models\Library\Reports;

use App\Common\TC_PDF;

class TableReportModel extends TC_PDF
{
    private $pictureRatio = 1.4;
    private $chartWidth = 80;
    private $chartHeight = 80;
    protected $title;
    protected $centered;

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

    public function createReport($results, $info, $file)
    {
        $this->AddPage($this->orientation);

        $pageHeight = $this->getCustomPageHeight();
        $bottomMargin = $this->getCustomPageBottomMargin();

        $info['columnInfo'] = $this->setReportColumnWidth($info['columnInfo'],
                $results);

        $this->outputReportHeader($info['columnInfo'], 0);

        $rowKeys = [];
        $columnKey = 0;
        $columnInfo = $info['columnInfo'];

        foreach ($results as $rowKey => $values) {

            $rowHeight = $this->getReportRowHeight($columnInfo, $values);

            if ($this->getY() + $rowHeight + $bottomMargin > $pageHeight) {
                // print page continuations
                $columnKey = $this->outputReportContinuations([
                    'columnKey' => $columnKey,
                    'results' => $results,
                    'rowKeys' => $rowKeys,
                    'columnInfo' => $columnInfo,
                ]);

                $this->AddPage($this->orientation);

                $this->outputReportHeader($columnInfo, 0);

                $rowKeys = [];
            }

            $rowKeys[] = $rowKey;

            $columnKey = $this->outputReportColumns([
                'rowHeight' => $rowHeight,
                'values' => $values,
                'columnInfo' => $columnInfo,
                'initColumn' => 0,
            ]);
        }

        if ($columnKey) {
            // print last page continuations
            $this->outputReportContinuations([
                'rowHeight' => $rowHeight,
                'columnKey' => $columnKey,
                'results' => $results,
                'rowKeys' => $rowKeys,
                'columnInfo' => $columnInfo,
            ]);
        }

        if (isset($info['charts'])) {
            $this->outputCharts($info['charts']);
        }

        $this->output($file, 'F');

        return $info;
    }

    /*
    ****************************************************************************
    */

    private function setReportColumnWidth($info, $results)
    {
        $colDivider = 3;

        foreach ($info as &$column) {

            $field = $column['field'];
            $column['colWidth'] = 0;

            foreach ($results as $values) {

                $textWidth = $this->GetStringWidth($values[$field]) + 4;

                $column['colWidth'] = max($textWidth, $column['colWidth']);
            }

            $column['colWidth'] = min(intdiv($column['width'], $colDivider),
                    $column['colWidth']);

            $captionWidth = $this->GetStringWidth($column['caption']) + 4;
            // caption should fit column width
            $column['colWidth'] = max($captionWidth, $column['colWidth']);
        }

        return $info;
    }

    /*
    ****************************************************************************
    */

    private function outputReportColumns($data)
    {
        $rowHeight = $data['rowHeight'];
        $values = $data['values'];
        $columnInfo = $data['columnInfo'];
        $initColumn = $data['initColumn'];

        $pageWidth = $this->getCustomPageWidth();
        $leftMargin = $this->getCustomPageLeftMargin();

        for ($columnKey=$initColumn; $columnKey<count($columnInfo); $columnKey++) {

            $column = $columnInfo[$columnKey];

            if ($this->getX() - $leftMargin + $column['colWidth'] > $pageWidth) {

                $this->Ln();

                return $columnKey;
            }

            $this->outputReportCell([
                'rowHeight' => $rowHeight,
                'column' => $column,
                'values' => $values,
                'field' => $column['field'],
            ]);
        }

        $this->Ln();

        return NULL;
    }

    /*
    ****************************************************************************
    */

    private function outputReportHeader($columnInfo, $initColumn)
    {
        $pageWidth = $this->getCustomPageWidth();
        $leftMargin = $this->getCustomPageLeftMargin();

        $this->customMultiCell([
            'width' => $pageWidth,
            'text' => $this->title,
            'align' => 'C',
        ]);

        for ($columnKey=$initColumn; $columnKey<count($columnInfo); $columnKey++) {

            $column = $columnInfo[$columnKey];

            if ($this->getX() - $leftMargin + $column['colWidth'] > $pageWidth) {
                break;
            }
            // set backgroud color to grey
            $this->SetFillColor(32, 32, 32, 0);

            $this->customMultiCell([
                'width' => $column['colWidth'],
                'text' => $column['caption'],
                'border' => 1,
                'align' => 'C',
                'ln' => 0,
                'fill' => TRUE,
            ]);
            // restore backgroud color
            $this->SetFillColor(0, 0, 0, 0);
        }

        $this->Ln();
    }

    /*
    ****************************************************************************
    */

    private function getReportRowHeight($columnInfo, $values)
    {
        $rowHeight = 0;

        foreach ($columnInfo as &$column) {
            // get columns width and estimate row height
            $field = $column['field'];

            if ($field == 'picture' && $values[$field]) {
                $height = ceil($column['colWidth'] * $this->pictureRatio);
            } else {
                $height = $this->getNumLines($values[$field], $column['colWidth']) *
                        $this->rowHeight;
            }

            $rowHeight = max($height, $rowHeight);
        }

        return $rowHeight;
    }

    /*
    ****************************************************************************
    */

    protected function outputReportImage($data)
    {
        $rowHeight = $data['rowHeight'];
        $column = $data['column'];
        $values = $data['values'];
        $field = $data['field'];

        $height = min($rowHeight - 2, $column['colWidth'] * $this->pictureRatio);

        $this->customImage([
            'file' => \App\Models\BaseModel::getStorageFolder() . $values[$field],
            'x' => $this->getX() + 1,
            'y' => $this->getY() + ceil(($rowHeight - $height) / 2),
            'w' => $column['colWidth'] - 2,
            'h' => $height,
        ]);

        $this->customMultiCell([
            'width' => $column['colWidth'],
            'height' => $rowHeight,
            'border' => 1,
            'ln' => 0,
            'maxh' => $rowHeight,
        ]);
    }

    /*
    ****************************************************************************
    */

    protected function outputReportCell($data)
    {
        $rowHeight = $data['rowHeight'];
        $column = $data['column'];
        $values = $data['values'];
        $field = $data['field'];

        if ($field == 'picture' && $values[$field]) {
            $this->outputReportImage($data);
        } else {

            $isCentered = isset($this->centered[$field]);

            $this->setCustomTextColor($field, $values[$field]);

            $value = $this->getCustomValue($field, $values[$field]);

            if ($field == 'source') {

                $this->SetTextColor(0, 0, 255);

                $this->customCell([
                    'width' => $column['colWidth'],
                    'height' => $rowHeight,
                    'text' => 'Download Link',
                    'border' => 1,
                    'align' => 'C',
                    'link' => \App\Models\BaseModel::getDownloadFolder() . $value,
                    'calign' => 'C',
                ]);

                $this->SetTextColor(0, 0, 0);

            } else {
                $this->customMultiCell([
                    'width' => $column['colWidth'],
                    'height' => $rowHeight,
                    'text' => $value,
                    'border' => 1,
                    'align' => $isCentered ? 'C' : 'L',
                    'ln' => 0,
                    'maxh' => $rowHeight,
                    'valign' => $isCentered ? 'M' : 'T',
                ]);
            }
            // restore text color
            $this->SetTextColor(255, 255, 255, 0);
        }
    }

    /*
    ****************************************************************************
    */

    protected function setCustomTextColor($field=NULL, $value=NULL)
    {
        // empty declaration: may be overriden by child classes
    }

    /*
    ****************************************************************************
    */

    protected function getCustomValue($field=NULL, $value=NULL)
    {
        // empty declaration: may be overriden by child classes
        return $value;
    }

    /*
    ****************************************************************************
    */

    private function outputReportContinuations($data)
    {
        $columnKey = $data['columnKey'];
        $results = $data['results'];
        $rowKeys = $data['rowKeys'];
        $columnInfo = $data['columnInfo'];

        if (! $columnKey) {
            // report first page is not a continuation - exit the function
            return 0;
        }

        do {
            // loop through page continuations and output their tables
            $initColumn = $columnKey;
            // each continuation starts from a new page
            $this->AddPage($this->orientation);

            $this->outputReportHeader($columnInfo, $initColumn);

            foreach ($rowKeys as $rowKey) {

                $rowHeight = $this->getReportRowHeight($columnInfo, $results[$rowKey]);

                $columnKey = $this->outputReportColumns([
                    'rowHeight' => $rowHeight,
                    'values' => $results[$rowKey],
                    'columnInfo' => $columnInfo,
                    'initColumn' => $initColumn,
                ]);
            }
        } while ($columnKey != NULL);

        return $columnKey;
    }

    /*
    ****************************************************************************
    */

    private function outputCharts($charts)
    {
        $pageWidth = $this->getCustomPageWidth();
        $pageHeight = $this->getCustomPageHeight();
        $topMargin = $this->getCustomPageTopMargin();

        $this->AddPage($this->orientation);

        $this->customMultiCell([
            'width' => $pageWidth,
            'text' => $this->title,
            'align' => 'C',
        ]);

        $chartX = 5;
        $chartY = $this->getCustomPageTopMargin() + $this->rowHeight;

        foreach ($charts as $key => $chart) {

            $chartWidthFactor = $chart['width-factor'] ?? 1;

            $chartWidth = round($this->chartWidth * $chartWidthFactor);

            if ($chartX + $chartWidth > $pageWidth) {
                $chartX = 5;
                $chartY = $this->getChartY($chartY + $this->rowHeight,
                        $pageHeight, $topMargin);
            }

            if ($chartY + $this->chartHeight + $this->rowHeight > $pageHeight) {

                $this->AddPage($this->orientation);

                $this->customMultiCell([
                    'width' => $pageWidth,
                    'text' => $this->title,
                    'align' => 'C',
                ]);

                $chartY = $this->rowHeight + $topMargin;
            }

            $this->customMultiCell([
                'width' => $chartWidth,
                'text' => $key,
                'align' => 'C',
                'fill' => FALSE,
                'ln' => 0,
                'x' => $chartX,
                'y' => $chartY,
            ]);

            $this->customImage([
                'file' => $chart['file'],
                'x' => $chartX,
                'y' => $chartY + $this->rowHeight,
                'w' => $chartWidth,
                'h' => $this->chartHeight,
            ]);

            $chartX += $chartWidth;
        }
    }

    /*
    ****************************************************************************
    */

    private function getChartY($chartY, $pageHeight, $topMargin)
    {
        if ($chartY + $this->chartHeight + $this->rowHeight > $pageHeight) {

            $this->AddPage($this->orientation);

            $chartY = $topMargin + $this->rowHeight;
        } else {
            $chartY += $this->chartHeight;
        }

        return $chartY;
    }

    /*
    ****************************************************************************
    */

}