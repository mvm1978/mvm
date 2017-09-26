<?php

namespace App\Http\Controllers\AbleTo;

use App\Http\Controllers\AbstractController;

use Illuminate\Http\Request;

use App\Models\AbleTo\AnswersModel;

class AnswersController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new AnswersModel();
    }

    /*
    ****************************************************************************
    */

    public function getTypeInfo(Request $request)
    {
        return $this->model->getTypeInfo();
    }

    /*
    ****************************************************************************
    */

}
