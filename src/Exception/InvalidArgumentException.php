<?php

namespace Spoonity\Exception;

use Exception;
use Spoonity\Exception\Abstraction\BaseException;

/**
 * Class InvalidArgumentException
 * @package App\Exception
 */
class InvalidArgumentException extends BaseException
{
    /** @var string */
    protected $argument;

    /** @var string */
    protected $reason;

    /** @var string */
    protected $class;

    /** @var string */
    protected $method;

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
