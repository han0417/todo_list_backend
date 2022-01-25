<?php


namespace App\Http\Resources;

class PostAdminStoreAccountLoginResource extends BaseJsonResource
{
    public function toArray($request)
    {
        return [
            'access_token' => $this['access_token'],
            'account'      => $this['user']['account'],
            'name'         => $this['user']['name']
        ];
    }
}
