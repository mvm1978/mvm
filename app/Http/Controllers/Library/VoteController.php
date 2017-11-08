<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;

use Illuminate\Http\Request;

use App\Models\Library\VoteModel;

class VoteController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->model = new VoteModel();
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
