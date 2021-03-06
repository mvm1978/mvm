<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

use App\Models\AuthenticationModel;

class BaseController extends Controller
{
    private $unauthPaths = [
        'GET' => [
            'genre' => TRUE,
            'genre/dropdown' => TRUE,
            'author' => TRUE,
            'author/dropdown' => TRUE,
            'author/count' => TRUE,
            'type' => TRUE,
            'type/dropdown' => TRUE,
            'type/count' => TRUE,
            'book' => TRUE,
            'book/download' => TRUE,
            'book/chart' => TRUE,
            'author/download-report-pdf' => TRUE,
            'book/download-report-pdf' => TRUE,
            'genre/download-report-pdf' => TRUE,
            'genre/count' => TRUE,
            'book/create-pdf' => TRUE,
            'book/top' => TRUE,
        ],
        'POST' => [
            'author/create-report-pdf' => TRUE,
            'book/create-report-pdf' => TRUE,
            'genre/create-report-pdf' => TRUE,
        ],
    ];

    private $model;
    protected $transformer;
    protected $userID;
    protected $token;
    protected $construct;

    /*
    ****************************************************************************
    */

    public function __construct(Request $request, $model)
    {
        $this->userID = 0;
        $this->model = $model;

        $parsed = explode('/', $request->getPathInfo());

        if (empty($parsed[4])) {
            return $this->construct = [
                'error' => [422 => 'resourse_not_defined'],
            ];
        }

        $method = $request->method();
        $firstResourse = $parsed[4];
        $fullResourse = implode('/', array_slice($parsed, 4, 2));

        if (! isset($this->unauthPaths[$method][$fullResourse])
         && ! isset($this->unauthPaths[$method][$firstResourse])) {

            // some requests may not need pior authorization
            $header = $request->header();

            if (empty($header['token']) || empty($header['id'])) {
                return $this->construct = [
                    'error' => [401 => 'invalid_token'],
                ];
            }

            $token = reset($header['token']);
            $id = reset($header['id']);

            if (! $token || ! $id) {
                return $this->construct = [
                    'error' => [401 => 'invalid_token'],
                ];
            }

            $model = new AuthenticationModel();

            $this->userID = $model->verifyToken($token, $id) ? $id : 0;

            if (! $this->userID) {
                return $this->construct = [
                    'error' => [401 => 'invalid_token'],
                ];
            }
        }
    }

    /*
    ****************************************************************************
    */

    public function getDropdown(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        return $this->model->getDropdown();
    }

    /*
    ****************************************************************************
    */

    public function getCount(Request $request)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        return $this->model->getCount();
    }

    /*
    ****************************************************************************
    */

    protected function makeResponse($code, $message, $data=[])
    {
        return response()->json($data + ['message' => $message], $code);
    }

    /*
    ****************************************************************************
    */

    protected function constructErrorResponse()
    {
        $error = $this->construct['error'];

        $code = key($error);

        return $this->makeResponse($code, $error[$code]);
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

        $results = $this->model->getTableData($params);

        if ($this->transformer) {
            $this->transformer->transformCollection($results->all());
        }

        return $results;
    }

    /*
    ****************************************************************************
    */

    protected function patch(Request $request, $field, $id)
    {
        if (! empty($this->construct['error'])) {
            return $this->constructErrorResponse();
        }

        $payload = $request->toArray();

        $upload = $payload['upload'] ?? NULL;

        if (is_object($upload)) {
            // updating an image
            $uploadImage = $this->getUploadImage('authors', $upload);

            if ($uploadImage['error']) {
                return $uploadImage['error'];
            }

            $payload = [
                'value' => $uploadImage['file'],
            ];
        }

        $result = $this->model->patchField($field, $id, $payload);

        return $result ? $this->makeResponse(200, 'patch_successful') :
            $this->makeResponse(500, 'patch_error');
    }

    /*
    ****************************************************************************
    */

    protected function getUploadImage($module, $upload)
    {
        if (substr($upload->getMimeType(), 0, 5) != 'image') {
            return [
                'error' => $this->makeResponse(422, 'invalid_upload_mime_type'),
                'file' => NULL,
            ];
        } elseif (! $upload->getSize()) {
            return [
                'error' => $this->makeResponse(422, 'empty_upload_file'),
                'file' => NULL,
            ];
        } elseif ($upload->getSize() > 1024 * 1024 * 5) {
            return [
                'error' => $this->makeResponse(422, 'invalid_upload_size'),
                'file' => NULL,
            ];
        }

        $path = 'images' . DIRECTORY_SEPARATOR . $module;

        return [
            'error' => NULL,
            'file' => $this->getStorageFileName($upload, $path),
        ];
    }

    /*
    ****************************************************************************
    */

    protected function getUploadSource($upload)
    {
        $mimeType = $upload->getMimeType();

        if (! in_array($mimeType, ['application/pdf', 'audio/mpeg'])) {
            return [
                'error' => $this->makeResponse(422, 'invalid_upload_mime_type'),
                'file' => NULL,
            ];
        } elseif (! $upload->getSize()) {
            return [
                'error' => $this->makeResponse(422, 'empty_upload_file'),
                'file' => NULL,
            ];
        }

        $module = $mimeType == 'application/pdf' ? 'paper' : 'audio';

        $path = 'sources' . DIRECTORY_SEPARATOR . $module;

        return [
            'error' => NULL,
            'file' => $this->getStorageFileName($upload, $path),
        ];
    }

    /*
    ****************************************************************************
    */

    private function getStorageFileName($upload, $path)
    {
        $fileName = round(microtime(TRUE) * 10000);
        $extension = $upload->getClientOriginalExtension();

        $storageFileName = $fileName . '.' . $extension;

        $upload->move(public_path($path), $storageFileName);

        copy(public_path() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $storageFileName,
                \App\Models\BaseModel::getStorageFolder() . $storageFileName);

        return $storageFileName;
    }

    /*
    ****************************************************************************
    */

}
