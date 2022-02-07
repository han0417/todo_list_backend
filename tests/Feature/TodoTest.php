<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Constants\AccountConstant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TodoTest extends TestCase
{
    use DatabaseTransactions;
    const URL = '/api/todos';
    const LOGIN_URL = '/api/login';

    //測試新增todo 清單
    public function testCreate()
    {
        $userId = $this->insertAccountReturnId();
        $token = $this->getToken();
        $url = self::URL;
        //建立測試資料
        $inputData = [
            'title'   => 'test',
            'checked' => false,
            'user_id' => $userId
        ];
        $response = $this->json('POST', $url, $inputData, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
    }

    //測試取得todo 清單
    public function testGetList()
    {

        $userId = $this->insertAccountReturnId();
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $this->insertTodoReturnId($userId);
        $response = $this->json('GET', $url, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        //dd($response);
        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
        $response->assertJsonStructure([
            'response' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'checked'
                    ]
                ]
            ],
            'request_id',
            'code',
            'status',
            'message',
        ]);
    }



    //測試取得todo 個別資料
    public function testGetOne()
    {
        $userId = $this->insertAccountReturnId();
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id = $this->insertTodoReturnId($userId);

        $response = $this->json('GET', sprintf('%s/%s', $url , $id), [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
        $response->assertJsonStructure([
            'response' => [
                'id',
                'title',
                'checked'
            ],
            'request_id',
            'code',
            'status',
            'message',
        ]);
    }

    //測試取得todo 個別資料失敗
    public function testGetOneFail()
    {
        $userId = $this->insertAccountReturnId();
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id = $this->insertTodoReturnId($userId);
        $response = $this->json('GET', $url . '/-1', [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(400);
        $response->assertJson([
            'code' => 20001,
            'status' => false
        ]);
        $response->assertJsonStructure([
            'code',
            'status',
            'message',
            'detail',
        ]);
    }

    //測試更新todo
    public function testUpdate()
    {
        $userId = $this->insertAccountReturnId();
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id = $this->insertTodoReturnId($userId);

        $inputData = [
            'title'   => 'test',
            'checked' => true,
            'user_id' => $userId
        ];
        $response = $this->json('PUT', sprintf('%s/%s', $url , $id), $inputData, [
            'Authorization' => 'Bearer ' . $token
        ]);
        //dd($response);
        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
    }

    //測試更新todo
    public function testDelete()
    {
        //取得登入Token
        $userId = $this->insertAccountReturnId();
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id = $this->insertTodoReturnId($userId);

        $response = $this->json('DELETE', sprintf('%s/%s', $url , $id), [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        //dd($response);
        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
    }

    /**
     *  建立測試帳號
     *  @return int $userId 用戶序號
     *
     */
    private function insertAccountReturnId()
    {
        $userId = DB::table('admin_store_accounts')->insertGetId([
            'name'    => 'test',
            'account' => 'test',
            'password' => Hash::make('123qwe'),
        ]);
        return $userId;
    }
    /**
     *  取得Token
     *  @return int $token jwt令牌
     *
     */
    private function getToken()
    {
        //取得登入Token
        $loginUrl = self::LOGIN_URL;
        //測試帳號
        $loginData = AccountConstant::TEST_ACCOUNT;
        $token = $this->json('POST', $loginUrl, $loginData)['response']['access_token'];
        return $token;
    }

    /**
     *  建立測試資料
     *  @param  int $userId 用戶序號
     *  @return int $id     todo序號
     *
     */
    private function insertTodoReturnId($userId)
    {
        $id = DB::table('todos')->insertGetId([
            'title'   => 'test',
            'checked' => false,
            'user_id' => $userId
        ]);

        return $id;
    }
}
