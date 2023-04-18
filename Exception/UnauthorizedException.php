<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class UnauthorizedException
 * @package Spoonity\Common\Exception
 */
class UnauthorizedException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 401;

    /** @var string  */
    protected $message = 'Unauthorized';

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
