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
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //建立測試資料
        $inputData = [
            'title'   => 'test',
            'checked' => false,
            'user_id' => 1
        ];
        $response = $this->json('POST', $url, $inputData, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'test',
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
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        \App\Models\Todo::factory()->create(['user_id' => 1]);
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
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id =  \App\Models\Todo::factory()->create(['user_id' => 1])->id;
        $response = $this->json('GET', sprintf('%s/%s', $url, $id), [], [
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
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        $response = $this->json('GET', sprintf('%s/%s', $url, 1), [], [
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
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id = \App\Models\Todo::factory()->create(['user_id' => 1])->id;

        $inputData = [
            'title'   => 'update',
            'checked' => true,
            'user_id' => 1
        ];
        $response = $this->json('PUT', sprintf('%s/%s', $url, $id), $inputData, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'update',
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
        //取得token
        $token = $this->getToken();
        $url = self::URL;
        //新增測試資料
        $id =  \App\Models\Todo::factory()->create(['user_id' => 1])->id;

        $response = $this->json('DELETE', sprintf('%s/%s', $url, $id), [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        //軟刪除專用
        $this->assertSoftDeleted('todos', [
            'id' => $id,
        ]);
        //dd($response);
        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
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
        $loginData = [
            'account'  => AccountConstant::TEST_ACCOUNT['account'],
            'password' => AccountConstant::TEST_ACCOUNT['password']
        ];
        $token = $this->json('POST', $loginUrl, $loginData)['response']['access_token'];
        return $token;
    }
}
