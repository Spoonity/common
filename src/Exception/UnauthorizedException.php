<?php
/**
 * Created by PhpStorm.
 * User: misfitpixel
 * Date: 3/28/19
 * Time: 1:52 PM
 */

namespace Spoonity\Exception;


use Spoonity\Exception\Abstraction\BaseException;

/**
 * Class UnauthorizedException
 * @package Spoonity\Exception
 */
class UnauthorizedException extends BaseException
{
    /** @var int  */
    protected $statusCode = 401;

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