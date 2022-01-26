<?php

namespace App\Exceptions;

use App\Constants\ExceptionConstant;
use Throwable;

/**
 * Class AddressException
 *
 * @package App\Exceptions
 */
class TodoException extends BaseException
{
    private $errorConfig = [
        '20001' => [
            'type'    => ExceptionConstant::FAILURE,
            'message' => '取得資料失敗，請重試',
            'sentry'  => false,
        ],
        '20002' => [
            'type'    => ExceptionConstant::FAILURE,
            'message' => '新增失敗，請重試',
            'sentry'  => false,
        ],
        '20003' => [
            'type'    => ExceptionConstant::FAILURE,
            'message' => '更新失敗，請重試',
            'sentry'  => false,
        ],
        '20004' => [
            'type'    => ExceptionConstant::FAILURE,
            'message' => '刪除失敗，請重試',
            'sentry'  => false,
        ]
    ];

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setErrorConfig($this->errorConfig);
    }
}
