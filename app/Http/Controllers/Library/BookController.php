<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;

use Illuminate\Http\Request;

use App\Models\Library\BookModel;

class BookController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new BookModel();
    }

    /*
    ****************************************************************************
    */

    public function get(Request $request, $id=NULL)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        return $this->model->getBooks();
    }

    /*
    ****************************************************************************
    */

}
