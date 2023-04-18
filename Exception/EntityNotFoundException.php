<?php

namespace Spoonity\Common\Exception;


use Spoonity\Common\Exception\Abstraction\BaseException;

/**
 * Class EntityNotFoundException
 * @package Spoonity\Common\Exception
 */
class EntityNotFoundException extends BaseException
{
    /** @var int  */
    protected int $statusCode = 404;

    /** @var string  */
    protected $message = "Entity not found";

    /** @var string */
    protected string $entity;

    /**
     * EntityNotFoundException constructor.
     * @param null|string $message
     * @param null $entity
     * @param \Exception|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(?string $message = null, $entity = null, ?\Exception $previous = null, array $headers = array(), ?int $code = 0)
    {
        $this->entity = strtolower(str_replace('App\Entity\\', '', $entity));

        if($this->entity != null) {
            $this->message = sprintf('%s not found', ucfirst($this->entity));
        }

        parent::__construct($message, $previous, $headers, $code);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'entity' => $this->entity
        ];
    }
}
