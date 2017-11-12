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
        parent::__construct($request);

        $this->model = new BookModel();
        $this->transformer = new BookTransformer();
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

        $results = $this->model->getTableData($params)->all();

        return $this->transformer->transformCollection($results);
    }

    /*
    ****************************************************************************
    */

}
