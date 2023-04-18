<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class RequestConflictException
 * @package Spoonity\Common\Exception
 */
class RequestConflictException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 409;

    /** @var string  */
    protected $message = 'Conflict';

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
