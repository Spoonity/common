<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class TimeoutException
 * @package Spoonity\Common\Exception
 */
class TimeoutException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 408;

    /** @var string  */
    protected $message = "Request Timeout";

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
