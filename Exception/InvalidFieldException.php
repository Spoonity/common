<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class InvalidFieldException
 * @package Spoonity\Common\Exception
 */
class InvalidFieldException extends BaseException
{
    /** @var int */
    protected int $statusCode = 400;

    /** @var string */
    protected $message = 'Bad Request';

    /** @var string */
    protected string $field;

    /**
     * InvalidFieldException constructor.
     * @param null|string $message
     * @param null|string $field
     * @param \Exception|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(?string $message = null, ?string $field = null, ?\Exception $previous = null, array $headers = array(), ?int $code = 0)
    {
        $this->field = $field;

        parent::__construct($message, $previous, $headers, $code);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'field' => $this->field
        ];
    }
}
