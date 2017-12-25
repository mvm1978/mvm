<?php

namespace App\Common;

use Elibyy\TCPDF\TCPDF;

class TC_PDF extends TCPDF
{
    public $rowHeight = 6;

    /*
    ****************************************************************************
    */

    public function getCustomRowAmount($height=NULL)
    {
        $rowHeight = $height ?? $this->rowHeight;

        $pageDimensions = $this->getPageDimensions();

        $margins = ceil($pageDimensions['tm'] + $pageDimensions['bm']);

        $pageHeight = $this->getCustomPageHeight() - $margins;

        return intdiv($pageHeight, $rowHeight) - 1;
    }

    /*
    ****************************************************************************
    */

    public function getCustomPageWidth()
    {
        $pageDimensions = $this->getPageDimensions();

        $margins = $pageDimensions['lm'] + $pageDimensions['rm'];

        return (int)($pageDimensions['wk'] - $margins);
    }

    /*
    ****************************************************************************
    */

    public function getCustomPageHeight($pagenum=NULL)
    {
        return ceil($this->getPageDimensions($pagenum)['hk']);
    }

    /*
    ****************************************************************************
    */

    public function getCustomPageTopMargin($pagenum=NULL)
    {
        return ceil($this->getPageDimensions($pagenum)['tm']);
    }

    /*
    ****************************************************************************
    */

    public function getCustomPageBottomMargin($pagenum=NULL)
    {
        return ceil($this->getPageDimensions($pagenum)['bm']);
    }

    /*
    ****************************************************************************
    */

    public function getCustomPageLeftMargin($pagenum=NULL)
    {
        return (int)$this->getPageDimensions($pagenum)['lm'];
    }

    /*
    ****************************************************************************
    */

    public function getCustomPageRightMargin($pagenum=NULL)
    {
        return (int)$this->getPageDimensions($pagenum)['rm'];
    }

    /*
    ****************************************************************************
    */

    public function customCell($data)
    {
        $data['height'] = $data['height'] ?? $this->rowHeight;
        $data['border'] = $data['border'] ?? 0;
        $data['ln'] = $data['ln'] ?? 0;
        $data['align'] = $data['align'] ?? NULL;
        $data['fill'] = $data['fill'] ?? FALSE;
        $data['link'] = $data['link'] ?? NULL;
        $data['stretch'] = $data['stretch'] ?? 0;
        $data['ignore_min_height'] = $data['ignore_min_height'] ?? FALSE;
        $data['calign'] = $data['calign'] ?? 'T';
        $data['valign'] = $data['valign'] ?? 'M';
        // Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
        $this->Cell($data['width'], $data['height'], $data['text'], $data['border'],
                $data['ln'], $data['align'], $data['fill'], $data['link'], $data['stretch'],
                $data['stretch'], $data['ignore_min_height'], $data['calign'], $data['valign']);
    }

    /*
    ****************************************************************************
    */

    public function customMultiCell($data)
    {
        $data['text'] = $data['text'] ?? NULL;
        $data['height'] = $data['height'] ?? $this->rowHeight;
        $data['border'] = $data['border'] ?? 0;
        $data['align'] = $data['align'] ?? 'J';
        $data['fill'] = $data['fill'] ?? FALSE;
        $data['ln'] = $data['ln'] ?? 1;
        $data['x'] = $data['x'] ?? NULL;
        $data['y'] = $data['y'] ?? NULL;
        $data['reseth'] = $data['reseth'] ?? TRUE;
        $data['stretch'] = $data['stretch'] ?? 0;
        $data['ishtml'] = $data['ishtml'] ?? FALSE;
        $data['autopadding'] = $data['autopadding'] ?? TRUE;
        $data['maxh'] = $data['maxh'] ?? 0;
        $data['valign'] = $data['valign'] ?? 'T';
        $data['fitcell'] = $data['fitcell'] ?? FALSE;

        $this->MultiCell($data['width'], $data['height'], $data['text'], $data['border'],
                $data['align'], $data['fill'], $data['ln'], $data['x'], $data['y'],
                $data['reseth'], $data['stretch'], $data['ishtml'], $data['autopadding'],
                $data['maxh'], $data['valign'], $data['fitcell']);
    }

    /*
    ****************************************************************************
    */

    public function customImage($data)
    {
        $data['x'] = $data['x'] ?? NULL;
        $data['y'] = $data['y'] ?? NULL;
        $data['w'] = $data['w'] ?? 0;
        $data['h'] = $data['h'] ?? 0;
        $data['type'] = $data['type'] ?? NULL;
        $data['link'] = $data['link'] ?? NULL;
        $data['align'] = $data['align'] ?? NULL;
        $data['resize'] = $data['resize'] ?? FALSE;
        $data['dpi'] = $data['dpi'] ?? 300;
        $data['palign'] = $data['palign'] ?? NULL;
        $data['ismask'] = $data['ismask'] ?? FALSE;
        $data['imgmask'] = $data['imgmask'] ?? FALSE;
        $data['border'] = $data['border'] ?? 0;
        $data['fitbox'] = $data['fitbox'] ?? FALSE;
        $data['hidden'] = $data['hidden'] ?? FALSE;
        $data['fitonpage'] = $data['fitonpage'] ?? FALSE;

        $this->Image($data['file'], $data['x'], $data['y'], $data['w'], $data['h'],
                $data['type'], $data['link'], $data['align'], $data['resize'],
                $data['dpi'], $data['palign'], $data['ismask'], $data['imgmask'],
                $data['border'], $data['fitbox'], $data['hidden'], $data['fitonpage']);
    }

    /*
    ****************************************************************************
    */

}
