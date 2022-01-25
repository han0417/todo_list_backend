<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminStoreAccountService;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\PostAdminStoreAccountLoginResource;

class AdminStoreAccountController extends Controller
{
    private $accountService;

    public function __construct(
        AdminStoreAccountService $accountService
    ) {
        $this->accountService = $accountService;
    }

    /**
     * 登入
     * @return json
     */
    public function login(Request $request)
    {
        $inputData    = $request->all();
        $validateRule = [
            'account'  => 'required|string',
            'password' => 'required|string',
        ];
        $this->validateByRule($inputData, $validateRule);

        $loginData = $this->accountService->verifyAccount($inputData);

        return new PostAdminStoreAccountLoginResource($loginData);
    }

    /**
     * 登出
     * @return BaseJsonResource
     */
    public function logout()
    {
        $this->accountService->logout();
        return new BaseJsonResource(null);
    }

    /**
     * 登出
     * @return BaseJsonResource
     */
    public function test()
    {
        return new BaseJsonResource(null);
    }

    /**
     * create user
     */
    public function create(Request $request)
    {
        $inputData = $request->all();
        $validateRule = [
            'account'     => 'required|string',
            'password'    => 'required|string',
            'name'        => 'required|string'
        ];
        $this->validateByRule($inputData, $validateRule);
        $this->accountService->createAccount($inputData);
        return new BaseJsonResource(null);
    }
}
