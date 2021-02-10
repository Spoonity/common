<?php


namespace Spoonity\Exception;


use Spoonity\Exception\Abstraction\BaseException;

/**
 * Class NotImplementedException
 * @package Spoonity\Exception
 */
class NotImplementedException extends BaseException
{
    /** @var int  */
    protected $statusCode = 501;

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