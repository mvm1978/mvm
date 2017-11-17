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

        $body = $request->toArray();

        $genre = $body['genre'];

        $genreExists = $this->model->getByName($genre);

        if ($genreExists) {
            return $this->makeResponse(422, 'genre_exists');
        }

        $this->model->createGenre($genre);

        return $this->makeResponse(200, 'genre_upload_successful');
    }

    /*
    ****************************************************************************
    */

    public function fetch(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $params = $request->all();

        $result = $this->model->getTableData($params);

        return $result;
    }

    /*
    ****************************************************************************
    */

    public function patch(Request $request, $id)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $payload = $request->all();

        $result = $this->model->patchField($payload, $id);

        return $result ? $this->makeResponse(200, 'patch_successful') :
            $this->makeResponse(500, 'patch_error');
    }

    /*
    ****************************************************************************
    */

}
