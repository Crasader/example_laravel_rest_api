<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponseHelper;
use App\User;
use Illuminate\Http\Request;

abstract class AbstractApiController extends Controller
{
    private const EMPTY_MODEL_ATTRIBUTES = [
        'id' => 0,
        'firstname' => '',
        'lastname' => '',
        'email' => '',
    ];

    protected $response;
    protected $repository;
    protected $user;
    protected $request;

    public function __construct(Request $request)
    {
        $this->response = ApiResponseHelper::getInstance();
        $this->repository = resolve($this->getRepository());

        $this->user = new User();
        $this->user->forceFill(self::EMPTY_MODEL_ATTRIBUTES);

        dd($request->user());

        if ($request->user()) {
            $this->user = $request->user()->toArray();
        }

        $this->request = $request;
    }

    abstract protected function getRepository() : string;
}
