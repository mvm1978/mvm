<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\TypeModel;

class TypeController extends LibraryController
{
    public function __construct(Request $request)
    {
        $this->model = new TypeModel();

        parent::__construct($request, $this->model);
    }

    /*
    ****************************************************************************
    */

}
