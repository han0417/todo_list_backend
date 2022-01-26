<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Constants\PlatformConstant;
use App\Repositories\AdminStoreAccountRepository;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Hash;

class AdminStoreAccountService
{
    private $adminStoreAccountRepo;
    private $exception;

    public function __construct(
        AdminStoreAccountRepository $adminStoreAccountRepo,
        UserException $exception
    ) {
        $this->adminStoreAccountRepo = $adminStoreAccountRepo;
        $this->exception             = $exception;
    }

    /**
     * verify user data
     * @param array $data [
     *    @param string account  帳號
     *    @param string password 密碼
     * ]
     *
     * @return array
     */
    public function verifyAccount(array $data)
    {
        if (!$token = auth('admin')->attempt($data)) {
            $this->exception->error(20001);
        }

        return [
            'access_token' => $token,
            'user'         => auth('admin')->user()
        ];
    }

    /**
     * 登出
     * @return null
     */
    public function logout()
    {
        try {
            auth('admin')->logout();
        } catch (\Exception $e) {
            $this->exception->error(20002, $e->getMessage());
        }
    }

    /**
     * create user data
     * @param array $data [
     *    @param string account  帳號
     *    @param string password 密碼
     *    @param string name     用戶名稱
     * ]
     *
     * @return void
     */
    public function createAccount(array $data)
    {
        $accountData = [
            'account'     => $data['account'],
            'password'    => $data['password'],
            'name'        => $data['name'],
        ];

        DB::beginTransaction();
        try {
            $this->adminStoreAccountRepo->create($accountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->exception->error(20003, $e->getMessage());
        }
    }
}
