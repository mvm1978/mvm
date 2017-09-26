<?php

namespace App\Http\Controllers\AbleTo;

use App\Http\Controllers\AbstractController;

use Illuminate\Http\Request;

use App\Models\AbleTo\QuizResultsModel;

class QuizResultsController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new QuizResultsModel();
    }

    /*
    ****************************************************************************
    */

    public function create(Request $request, $userID)
    {
        return $this->model->create($request->toArray(), $userID);
    }

    /*
    ****************************************************************************
    */

    public function getTotals(Request $request, $userID)
    {
        return $this->model->getTotals($userID);
    }

    /*
    ****************************************************************************
    */

}
