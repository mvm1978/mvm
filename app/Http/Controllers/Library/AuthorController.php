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

        $body = $request->toArray();

        $picture = NULL;
        $author = $body['authorName'];
        $description = $body['description'];
        $upload = $body['upload'];

        $authorExists = $this->model->getByName($author);

        if ($authorExists) {
            return $this->makeResponse(422, 'author_exists');
        }

        if (is_object($upload)) {
            if (substr($upload->getMimeType(), 0, 5) != 'image') {
                return $this->makeResponse(422, 'invalid_upload_mime_type');
            } elseif (! $upload->getSize()) {
                return $this->makeResponse(422, 'empty_upload_file');
            } elseif ($upload->getSize() > 1024 * 1024 * 5) {
                return $this->makeResponse(422, 'invalid_upload_size');
            }

            $fileName = round(microtime(true) * 1000);
            $extension = $upload->getClientOriginalExtension();

            $picture = $fileName . '.' . $extension;

            $upload->move(public_path('images/authors'), $picture);

            copy(public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'authors' . DIRECTORY_SEPARATOR . $picture,
                    storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $picture);
        }

        $this->model->createAuthor($author, $description, $picture);

        return $this->makeResponse(200, 'image_upload_successful');
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
