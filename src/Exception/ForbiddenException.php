<?php
/**
 * Created by PhpStorm.
 * User: misfitpixel
 * Date: 3/28/19
 * Time: 1:46 PM
 */

namespace Spoonity\Exception;


use Spoonity\Exception\Abstraction\BaseException;

/**
 * Class ForbiddenException
 * @package Spoonity\Exception
 */
class ForbiddenException extends BaseException
{
    /** @var int  */
    protected $statusCode = 403;

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