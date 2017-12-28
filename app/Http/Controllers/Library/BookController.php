<?php

namespace App\Http\Controllers\Library;

use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Library\LibraryController;

use Illuminate\Http\Request;

use App\Models\Library\BookModel;
use App\Models\Library\VoteModel;
use App\Transformers\Library\BookTransformer;

class BookController extends LibraryController
{
    private $voteModel;

    public function __construct(Request $request)
    {
        $this->model = new BookModel();
        $this->voteModel = new VoteModel();
        $this->transformer = new BookTransformer();

        parent::__construct($request, $this->model);
    }

    /*
    ****************************************************************************
    */

    public function download(Request $request, $fileName)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $file = $this->model->getStorageFolder() . $fileName;

        $headers = [
            'Content-Type: application/pdf',
        ];

        return Response::download($file, $fileName, $headers);
    }

    /*
    ****************************************************************************
    */

    public function vote(Request $request, $id)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $payload = $request->toArray();

        if (! in_array($payload['vote'], ['up', 'down'])) {
            return $this->makeResponse(200, 'invalid_vote_type');
        }

        $prevVote = $this->voteModel->vote($this->userID, $id, $payload['vote']);

        $result = $this->model->vote($id, $prevVote, $payload['vote']);

        return $result ? $this->makeResponse(200, 'vote_accepted', $result) :
                $this->makeResponse(500, 'vote_error');
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

        return $result ? $this->makeResponse(200, 'book_created', $result) :
                $this->makeResponse(500, 'error_creating_book');
    }

    /*
    ****************************************************************************
    */

    public function chart(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $result = $this->model->getCharts();

        return $result ? $this->makeResponse(200, 'charts_created', $result) :
                $this->makeResponse(500, 'error_creating_charts');
    }

    /*
    ****************************************************************************
    */

    public function createPDF(Request $request, $title, $author)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $model = $this->model;

        $bookTitle = ucwords(str_replace('_', ' ', $title));
        $bookAuthor = ucwords(str_replace('_', ' ', $author));
        $file = $this->model->getTempFolder() . $bookTitle . '.pdf';

        $model->createPDF($bookTitle, $bookAuthor, $file);

        return $this->makeResponse(200, 'report_created');
    }

    /*
    ****************************************************************************
    */

    public function getTop(Request $request, $amount)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        return $this->model->getTop($amount);
    }

    /*
    ****************************************************************************
    */

}
