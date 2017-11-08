<?php

namespace App\Http\Controllers;

class AbstractController extends Controller
{
    protected $model = NULL;

    /*
    ****************************************************************************
    */

    public function __construct($request)
    {
        parent::__construct($request);
    }

    /*
    ****************************************************************************
    */

    public function get(Request $request, $id=NULL)
    {
        $model = $this->model;

        if ($id) {
            $model->where($model->primeKey, $id)
                    ->orderBy($model->primeKey);
        }

        return $model->get();
    }

    /*
    ****************************************************************************
    */

}
