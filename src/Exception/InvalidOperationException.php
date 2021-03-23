<?php

namespace Spoonity\Exception;

use Exception;
use Spoonity\Exception\Abstraction\BaseException;

/**
 * Class InvalidOperationException
 * @package App\Exception
 */
class InvalidOperationException extends BaseException
{
    /** @var string */
    protected $reason;

    /** @var string */
    protected $class;

    /** @var string */
    protected $method;

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
