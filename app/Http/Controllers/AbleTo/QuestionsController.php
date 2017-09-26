<?php

namespace App\Http\Controllers\AbleTo;

use App\Http\Controllers\AbstractController;

use Illuminate\Http\Request;

use App\Models\AbleTo\QuestionsModel;

class QuestionsController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new QuestionsModel();
    }

    /*
    ****************************************************************************
    */

}
