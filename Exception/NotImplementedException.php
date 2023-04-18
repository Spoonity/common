<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class NotImplementedException
 * @package Spoonity\Common\Exception
 */
class NotImplementedException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 501;

    /** @var string  */
    protected $message = 'Not implemented';

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
