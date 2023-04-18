<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class UnknownErrorException
 * @package Spoonity\Common\Exception
 */
class UnknownErrorException extends BaseException
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
