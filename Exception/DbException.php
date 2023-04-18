<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class DbException
 * @package Spoonity\Common\Exception
 */
class DbException extends BaseException
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
