<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\GenreModel;

class GenreController extends LibraryController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new GenreModel();
    }

    /*
    ****************************************************************************
    */

    public function fetch(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        return $this->model->getGenres();
    }

    /*
    ****************************************************************************
    */

}
