<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class BadRequestException
 * @package Spoonity\Common\Exception
 */
class BadRequestException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 400;

    /** @var string  */
    protected $message = 'Bad request';

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
