<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AbstractRepository;
use App\Wrappers\ApiResponseHelper;
use App\User;
use Illuminate\Http\Request;

abstract class AbstractApiController extends Controller
{
    /**
     * @var ApiResponseHelper
     */
    protected $response;

    /**
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Request
     */
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

    /**
     * @return string
     */
    abstract protected function getRepository() : string;
}
