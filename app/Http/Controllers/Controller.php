<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Models\AuthenticationModel;

class Controller extends BaseController
{
    private $unauthPaths = [
        'users/create' => TRUE
    ];

    private $model;
    protected $userID;

    /*
    ****************************************************************************
    */

    public function __construct(Request $request)
    {
        $this->userID = NULL;

        $parsed = explode('/', $request->getPathInfo());

        if (empty($parsed[4]) || empty($parsed[5])) {
            throw new BadRequestHttpException('Class or method is not defined');
        }

        $class = $parsed[4];
        $method = $parsed[5];

        if (! isset($this->unauthPaths[$class . '/' . $method])) {
            // some requests may not need pior authorization
            $header = $request->header();

            if (empty($header['token']) || empty($header['id'])) {
                throw new UnauthorizedHttpException('Unauthorized');
            }

            $model = new AuthenticationModel();

            $token = reset($header['token']);
            $id = reset($header['id']);

            $this->userID = $model->verifyToken($token, $id);

            if (! $this->userID) {
                throw new UnauthorizedHttpException('Unauthorized');
            }
        }
    }

    /*
    ****************************************************************************
    */

}
