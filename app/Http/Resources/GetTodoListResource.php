<?php


namespace App\Http\Resources;


class GetTodoListResource extends BaseJsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this['id'],
            'title'       => $this['title'],
            'checked'     => boolval($this['checked'])
        ];
    }
}
