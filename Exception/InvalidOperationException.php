<?php

namespace Spoonity\Common\Exception;


use Exception;
use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class InvalidOperationException
 * @package Spoonity\Common\Exception
 */
class InvalidOperationException extends BaseException
{
    /** @var string */
    protected string $reason;

    /** @var string */
    protected string $class;

    /** @var string */
    protected string $method;

    /**
     * InvalidOperationException constructor.
     * @param string $reason
     * @param Exception|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(string $reason, ?Exception $previous = null, array $headers = [], ?int $code = 0)
    {
        $this->reason = $reason;

        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        $this->class = substr(strrchr($caller['class'], '\\'), 1);
        $this->method = $caller['function'];

        parent::__construct(sprintf(
            'Invalid call to %s%s%s(): %s',
            $this->class,
            $caller['type'],
            $this->method,
            $this->reason
        ), $previous, $headers, $code);
    }

    function getData(): array
    {
        return [
            'class' => $this->class,
            'method' => $this->method,
            'reason' => $this->reason,
        ];
    }
}
