<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\AuthorModel;

class AuthorController extends LibraryController
{
    public function __construct(Request $request)
    {
        $this->model = new AuthorModel();

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

        $payload = $request->toArray();

        foreach ($payload as $field => $value) {
            if (is_object($value)) {

                $uploadFile = $this->getUploadImage('authors', $value);

                if ($uploadFile['error']) {
                    return $uploadFile['error'];
                }

                $params[$field] = $uploadFile['file'];

            } else {
                $params[$field] = $value;
            }
        }

        $authorExists = $this->model->getByName($params['author']);

        if ($authorExists) {
            return $this->makeResponse(422, 'author_exists');
        }

        $result = $this->model->insertEntry($params);

        return $result ? $this->makeResponse(200, 'author_created', $result) :
                $this->makeResponse(500, 'error_creating_author');
    }

    /*
    ****************************************************************************
    */

}
