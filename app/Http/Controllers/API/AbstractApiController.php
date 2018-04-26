<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponseHelper;

abstract class AbstractApiController extends Controller
{
    protected $response;
    protected $repository;

    public function __construct()
    {
        $this->response = ApiResponseHelper::getInstance();
        $this->repository = resolve($this->getRepository());
    }

    abstract protected function getRepository() : string;
}
