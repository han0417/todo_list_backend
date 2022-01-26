<?php


namespace App\Http\Resources;

class GetTodoListCollection extends BaseResourceCollection
{
    public function toArray($request)
    {
        return GetTodoListResource::collection($this->collection);
    }
}
