<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\TodoRepository;
use App\Exceptions\TodoException;
use Carbon\Carbon;

class TodoService
{
    private $todoRepo;
    private $exception;

    public function __construct(
        todoRepository $todoRepo,
        TodoException $exception
    ) {
        $this->todoRepo = $todoRepo;
        $this->exception = $exception;
    }


    /**
     * get Todo list
     * @param array $data [
     *    @param int  limit   todo標題
     *    @param int  user_id 用戶序號
     * ]
     * @return object
     */
    public function getList(array $data)
    {
        $filter = [
            'limit'    => $data['limit'] ?? config('app.PAGE_LIMIT'),
            'user_id'  => $data['user_id']
        ];
        return $this->todoRepo->listByFilter($filter);
    }

    /**
     * get Todo
     * @param array $data [
     *    @param int  todo_id todo序號
     *    @param int  user_id 用戶序號
     * ]
     * @return object
     */

    public function getOne($inputData)
    {
        $filter = [
            'id'      => $inputData['todo_id'],
            'user_id' => $inputData['user_id']
        ];

        $todo = $this->todoRepo->listByFilter($filter)->first();
        //查無此Todo
        empty($todo) && $this->exception->error(20001);
        return $todo;
    }

    /**
     * create Todo
     * @param array $data [
     *    @param string  title   todo標題
     *    @param boolean checked 是否勾選
     *    @param int     user_id 用戶序號
     * ]
     * @return void
     */
    public function create(array $data)
    {
        $TodoData = [
            'title'        => $data['title'],
            'checked'      => $data['checked'],
            'user_id'      => $data['user_id']
        ];
        DB::beginTransaction();
        try {
            $this->todoRepo->create($TodoData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            //新增Todo失敗
            $this->exception->error(20002, $e->getMessage());
        }
    }

    /**
     * update Todo by id
    * @param array $data [
     *    @param string  title   todo標題
     *    @param boolean checked 是否勾選
     *    @param int     todo_id todo序號
     *    @param int     user_id 用戶序號
     * ]
     */
    public function update(array $data)
    {
        $todoData = [
            'title'   => $data['title'],
            'checked' => $data['checked']
        ];
        $this->getOne($data);
        DB::beginTransaction();
        try {
            $this->todoRepo->updateById($data['todo_id'], $todoData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //修改Todo失敗
            $this->exception->error(20003, $e->getMessage());
        }
    }

    /**
     * delete Todo by id
     * @param array $data [
     *    @param int  todo_id todo序號
     *    @param int  user_id 用戶序號
     * ]
     * @return void
     */
    public function delete($data)
    {
        $this->getOne($data);
        DB::beginTransaction();
        try {
            $this->todoRepo->deleteById($data['todo_id']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //刪除Todo失敗
            $this->exception->error(20004, $e->getMessage());
        }
    }
}
