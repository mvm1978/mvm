<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\BookModel;
use App\Transformers\Library\BookTransformer;

class BookController extends LibraryController
{
    public function __construct(Request $request)
    {
        $this->model = new BookModel();
        $this->transformer = new BookTransformer();

        parent::__construct($request, $this->model);
    }

    /*
    ****************************************************************************
    */

    public function fetch(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $params = $request->all();

        $results = $this->model->getTableData($params);

        $this->transformer->transformCollection($results->all());

        return $results;
    }

    /*
    ****************************************************************************
    */

    public function patch(Request $request, $id)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $payload = $request->all();

        $result = $this->model->patchField($payload, $id);

        return $result ? $this->makeResponse(200, 'patch_successful') :
            $this->makeResponse(500, 'patch_error');
    }

    /*
    ****************************************************************************
    */

}
