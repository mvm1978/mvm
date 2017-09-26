<?php

namespace App\Http\Controllers\AbleTo;

use App\Http\Controllers\AbstractController;

use Illuminate\Http\Request;

use App\Models\AbleTo\QuizzesModel;

class QuizzesController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new QuizzesModel();
    }

    /*
    ****************************************************************************
    */

    public function get(Request $request, $id=NULL)
    {
        return $this->model->getQuizzes();
    }

    /*
    ****************************************************************************
    */

}
