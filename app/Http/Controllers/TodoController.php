<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TodoService;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\GetTodoListCollection;
use App\Http\Resources\GetTodoResource;

class TodoController extends Controller
{
    private $todoService;

    public function __construct(
        TodoService $todoService
    ) {
        $this->todoService = $todoService;
    }

    /**
     * get todo list
     * @param int limit 顯示筆數限制
     * @param int page  頁數
     *
     * @return json
     */
    public function index(Request $request)
    {
        $inputData = $request->all();
        $inputData['user_id'] = auth('admin')->user()->id;
        $validateRule = [
            'limit'    => 'nullable|int',
            'page'     => 'nullable|int',
            'user_id'  => 'required|int'
        ];
        $this->validateByRule($inputData, $validateRule);

        $list = $this->todoService->getList($inputData);
        return new GetTodoListCollection($list);
    }

    /**
     * get todo
     * @param int $todoId todo 序號
     *
     * @return json
     */
    public function show($todoId)
    {
        $inputData = [
            'user_id' => auth('admin')->user()->id,
            'todo_id' => $todoId
        ];
        $validateRule = [
            'todo_id' => 'required|int',
        ];
        $this->validateByRule($inputData, $validateRule);

        $todo = $this->todoService->getOne($inputData);
        return new GetTodoResource($todo);
    }


    /**
     * create todo
     * @return BaseJsonResource
     */
    public function create(Request $request)
    {
        $inputData = $request->all();
        $inputData['user_id'] = auth('admin')->user()->id;
        $validateRule = [
            'title'        => 'required|string',
            'checked'      => 'required|boolean',
            'user_id'      => 'required|int'
        ];

        $this->validateByRule($inputData, $validateRule);

        $this->todoService->create($inputData);
        return new BaseJsonResource(null);
    }

    /**
     * update todo
     * @param int $todoId todo序號
     * @return BaseJsonResource
     */
    public function update(Request $request, $todoId)
    {
        $inputData = $request->all();
        $inputData['todo_id'] = $todoId;
        $inputData['user_id'] = auth('admin')->user()->id;

        $validateRule = [
            'todo_id'   => 'required|integer',
            'title'     => 'required|string',
            'checked'   => 'required|boolean',
            'user_id'   => 'required|int'
        ];
        $this->validateByRule($inputData, $validateRule);

        $this->todoService->update($inputData);
        return new BaseJsonResource(null);
    }

    /**
     * delete todo by id
     * @param int $todoId todo 序號
     *
     * @return BaseJsonResource
     */
    public function delete($todoId)
    {
        $inputData = [
            'user_id' => auth('admin')->user()->id,
            'todo_id' => $todoId
        ];
        $validateRule = [
            'todo_id' => 'required|integer',
            'user_id' => 'required|integer'
        ];
        $this->validateByRule($inputData, $validateRule);
        $this->todoService->delete($inputData);
        return new BaseJsonResource(null);
    }
}
