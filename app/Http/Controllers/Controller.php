<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $datempty($account->store_id)a
     * @param array $validateRule
     *
     * @return null
     * @throws
     */
    public function validateByRule(array $data, array $validateRule)
    {
        // 檢查資料正確性
        $validator = Validator::make($data, $validateRule);
        if ($validator->fails()) {
            // 參數錯誤
            //Log::debug('validate error' . $validator->errors()->first());
            app(ValidatorException::class)->error(801, implode(",", $validator->errors()->all()));
        }
    }
}
