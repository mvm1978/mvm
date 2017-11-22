<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\BookModel;
use App\Transformers\Library\BookTransformer;

class BookController extends LibraryController
{
    public function __construct(Request $request)
    {
        $this->model = new BookModel();
        $this->transformer = new BookTransformer();

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

        $params = [];
        $payload = $request->toArray();

        foreach ($payload as $field => $value) {
            if (is_object($value)) {

                $uploadFile = $field == 'source' ? $this->getUploadSource($value) :
                        $this->getUploadImage('books', $value);

                if ($uploadFile['error']) {
                    return $uploadFile['error'];
                }

                $params[$field] = $uploadFile['file'];

            } else {
                $params[$field] = $value;
            }
        }

        $params['upload_user_id'] = $this->userID;

        $result = $this->model->insertEntry($params);

        return $result ? $this->makeResponse(200, 'author_created', $result) :
                $this->makeResponse(500, 'error_creating_author');
    }

    /*
    ****************************************************************************
    */

}
