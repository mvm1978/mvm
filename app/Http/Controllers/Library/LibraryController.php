<?php

namespace App\Http\Controllers\Library;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

use App\Http\Controllers\BaseController;

class LibraryController extends BaseController
{
    public function __construct(Request $request, $model)
    {
        parent::__construct($request, $model);
    }

    /*
    ****************************************************************************
    */

    public function createReportPDF(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $model = $this->model;
        $payload = $request->toArray();
        $report = 'report.pdf';

        if (isset($payload['charts'])) {
            $payload['charts'] = $model->getChartImages($payload['charts']);
        }

        $file = \App\Models\BaseModel::getTempFolder() . $report;

        $model->createReport($payload, $file);

        return $this->makeResponse(200, 'report_created', ['report' => $report]);
    }

    /*
    ****************************************************************************
    */

    public function downloadReportPDF(Request $request, $fileName)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $file = \App\Models\BaseModel::getTempFolder() . $fileName;

        $headers = [
            'Content-Type: application/pdf',
        ];

        return Response::download($file, $fileName, $headers);
    }

    /*
    ****************************************************************************
    */

}
