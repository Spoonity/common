<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class ForbiddenException
 * @package Spoonity\Common\Exception
 */
class ForbiddenException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 403;

    /** @var string  */
    protected $message = 'Forbidden';

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
