<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponseHelper;
use App\User;
use Illuminate\Http\Request;

abstract class AbstractApiController extends Controller
{
    protected $response;
    protected $repository;
    protected $user;
    protected $request;

    public function __construct(Request $request)
    {
        $this->response = ApiResponseHelper::getInstance();
        $this->repository = resolve($this->getRepository());

        $this->user = new User();
        $this->user->forceFill(User::DEFAULT_USER_DATA);

        if (auth()->user()) {
            $this->user = auth()->user();
        }

        $this->request = $request;
    }

    abstract protected function getRepository() : string;
}
