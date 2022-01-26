<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository
{
    private $model;

    public function __construct(Todo $model)
    {
        $this->model = $model;
    }

    /**
     *
     * 取得員工清單
     * @return object
     *
     */
    public function listByFilter(array $filter, array $relation = [])
    {
        $query = $this->model->query();

        if (!empty($filter['id'])) {
            $query->where('id', $filter['id']);
        }

        if (!empty($filter['user_id'])) {
            $query->where('user_id', $filter['user_id']);
        }

        // 如果有limit就分頁
        return isset($filter['limit']) ? $query->paginate($filter['limit']) : $query->get();
    }

    /**
     *
     * create
     *
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * update by id
     */
    public function updateById(int $id, array $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * delete by id
     */
    public function deleteById(int $id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
