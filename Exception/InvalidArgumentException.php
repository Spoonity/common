<?php

namespace Spoonity\Common\Exception;


use Exception;
use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class InvalidArgumentException
 * @package Spoonity\Common\Exception
 */
class InvalidArgumentException extends BaseException
{
    /** @var string */
    protected string $argument;

    /** @var string */
    protected string $reason;

    /** @var string */
    protected string $class;

    /** @var string */
    protected string $method;

    /**
     * InvalidArgumentException constructor.
     * @param string $argument
     * @param string $reason
     * @param Exception|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(string $argument, string $reason, ?Exception $previous = null, array $headers = [], ?int $code = 0)
    {
        $this->argument = $argument;
        $this->reason = $reason;

        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        $this->class = substr(strrchr($caller['class'], '\\'), 1);
        $this->method = $caller['function'];

        parent::__construct(sprintf(
            'Invalid argument `%s` passed to %s%s%s(): %s',
            $this->argument,
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
            'argument' => $this->argument,
            'reason' => $this->reason,
        ];
    }
}
