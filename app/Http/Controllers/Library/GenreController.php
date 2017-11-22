<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\GenreModel;

class GenreController extends LibraryController
{
    public function __construct(Request $request)
    {
        $this->model = new GenreModel();

        parent::__construct($request, $this->model);
    }

    /*
    ****************************************************************************
    */

    public function upload(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $params = $request->toArray();

        $genreExists = $this->model->getByName($params['genre']);

        if ($genreExists) {
            return $this->makeResponse(422, 'genre_exists');
        }

        $result = $this->model->insertEntry($params);

        return $result ? $this->makeResponse(200, 'genre_created', $result) :
                $this->makeResponse(500, 'error_creating_genre');
    }

    /*
    ****************************************************************************
    */

}
