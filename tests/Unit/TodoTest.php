<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TodoService;
use App\Exceptions\TodoException;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoTest extends TestCase
{
    use DatabaseTransactions;

    private $todoServiceMock;
    private $todoService;

    public function setUp(): void
    {
        parent::setUp();
        $this->todoServiceMock = \Mockery::mock(TodoService::class);
        $this->todoService     = app(TodoService::class);
    }

    //測試 取得個別資料Service
    public function testGetOne()
    {
        //取得token
        $inputData = [
            'title'   => 'test',
            'checked' => false,
            'user_id' => 1
        ];

        $todoId = Todo::create($inputData)->id;
        $getData = [
            'todo_id' => $todoId,
            'user_id' => 1
        ];

        $result = $this->todoService->getOne($getData);

        $this->assertIsObject($result);
    }

    //測試 新增Service
    public function testCreate()
    {
        //取得token
        $inputData = [
            'title'   => 'test',
            'checked' => false,
            'user_id' => 1
        ];

        $mockRetrun = null;

        // act
        $this->todoServiceMock
            ->shouldReceive('create')
            ->once()
            ->with($inputData)
            ->andReturn($mockRetrun);

        $mockResult = $this->todoServiceMock->create($inputData);
        $result = $this->todoService->create($inputData);

        $this->assertSame($mockResult, $result);
    }

    //測試 更新Service
    public function testUpdate()
    {
        //取得token
        $inputData = [
            'title'   => 'test',
            'checked' => false,
            'user_id' => 1
        ];

        $todoId = Todo::create($inputData)->id;
        $updateData = [
            'title'   => 'test2',
            'checked' => false,
            'todo_id' => $todoId,
            'user_id' => 1
        ];

        $mockRetrun = null;

        // act
        $this->todoServiceMock
            ->shouldReceive('update')
            ->once()
            ->with($updateData)
            ->andReturn($mockRetrun);

        $mockResult = $this->todoServiceMock->update($updateData);
        $result = $this->todoService->update($updateData);

        $this->assertSame($mockResult, $result);
    }

    //測試 刪除Service
    public function testDelete()
    {
        //取得token
        $inputData = [
            'title'   => 'test',
            'checked' => false,
            'user_id' => 1
        ];

        $todoId = Todo::create($inputData)->id;

        $deleteData = [
            'user_id' => 1,
            'todo_id' => $todoId
        ];

        $mockRetrun = null;

        // act
        $this->todoServiceMock
            ->shouldReceive('delete')
            ->once()
            ->with($deleteData)
            ->andReturn($mockRetrun);

        $mockResult = $this->todoServiceMock->delete($deleteData);
        $result = $this->todoService->delete($deleteData);

        $this->assertSame($mockResult, $result);
    }

    //測試 找不到資料Exception
    public function testGetOneFail()
    {
        //取得token
        $inputData = [
            'title'   => 'test2',
            'checked' => false,
            'todo_id' => 1,
            'user_id' => 1
        ];

        $this->expectException(TodoException::class);
        $this->todoService->getOne($inputData);
    }
    
    //測試 更新Service 找不到資料Exception
    public function testUpdateNotFound()
    {
        //取得token
        $inputData = [
            'title'   => 'test2',
            'checked' => false,
            'todo_id' => 1,
            'user_id' => 1
        ];

        $this->expectException(TodoException::class);
        $this->todoService->update($inputData);
    }

    //測試 刪除Service 找不到資料Exception
    public function testDeleteNotFound()
    {
        //取得token
        $inputData = [
            'title'   => 'test2',
            'checked' => false,
            'todo_id' => 1,
            'user_id' => 1
        ];

        $this->expectException(TodoException::class);
        $this->todoService->delete($inputData);
    }
}
