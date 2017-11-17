<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

use App\Models\AuthenticationModel;

class BaseController extends Controller
{
    private $unauthPaths = [];

    private $model;
    protected $userID;
    protected $token;
    protected $construct;

    /*
    ****************************************************************************
    */

    public function __construct(Request $request, $model)
    {
        $this->userID = NULL;
        $this->model = $model;

        $parsed = explode('/', $request->getPathInfo());

        if (empty($parsed[4])) {
            return $this->construct = [
                'error' => [422 => 'resourse_not_defined'],
            ];
        }

        $method = $request->method();
        $resourse = $parsed[4];

        if (! isset($this->unauthPaths[$method][$resourse])) {
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

            $this->userID = $model->verifyToken($token, $id);

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
        return $this->model->getDropdown();
    }

    /*
    ****************************************************************************
    */

    protected function makeResponse($code, $message)
    {
        return response()->json([
            'message' => $message,
        ], $code);
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

}
