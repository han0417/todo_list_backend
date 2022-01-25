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
     *    @param string account
     *    @param string password
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
            $this->exception->error(20002);
        }
    }

    /**
     * create account
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

    /**
     * get user list
     * @param array $data
     * @return object
     */
    public function getUserList(array $data)
    {
        $filter = [
            'limit' => $data['limit'] ?? config('app.PAGE_LIMIT')
        ];
        return $this->userRepo->listByFilter($filter);
    }

    /**
     * get user
     * @param int $userId
     * @return object
     */
    public function getUser(int $userId)
    {
        $filter = [
            'id'    => $userId
        ];
        return $this->userRepo->listByFilter($filter);
    }

    /**
     * create user
     * @param array $data
     * @return object
     */
    public function createUser(array $data)
    {
        $userData = [
            'account'  => $data['account'],
            'password' => $data['password'],
            'name'     => $data['name'],
            'phone'    => $data['phone'] ?? null,
            'email'    => $data['email'] ?? null,
        ];

        DB::beginTransaction();
        try {
            $this->userRepo->create($userData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->exception->error(20003, $e->getMessage());
        }
    }

    /**
     * update user by id
     * @param int $userId
     */
    public function updateUserById(array $data)
    {
        $userData = [
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'email'    => $data['email'],
        ];

        DB::beginTransaction();
        try {
            $this->userRepo->updateById($data['id'], $userData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->exception->error(20004, $e->getMessage());
        }
    }

    /**
     * delete user by id
     * @param int $id
     */
    public function deleteUserById(int $id)
    {
        DB::beginTransaction();
        try {
            $this->userRepo->deleteById($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->exception->error(20004, $e->getMessage());
        }
    }
}
