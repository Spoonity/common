<?php

namespace Spoonity\Common\Exception\Controller;


use Spoonity\Common\Exception\Abstraction\BaseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twig\Environment;

/**
 * Class ExceptionController
 * @package Spoonity\Common\Exception\Controller
 */
class ExceptionController
{
    /** @var bool  */
    protected bool $debug;

    /** @var Environment  */
    protected Environment $twig;

    /**
     * ExceptionController constructor.
     * @param bool $debug
     * @param Environment $twig
     */
    public function __construct(bool $debug, Environment $twig)
    {
        $this->debug = $debug;
        $this->twig = $twig;
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if(!($exception instanceof BaseException)) {
            return;
        }

        if($exception->getTemplate() === null) {
            $event->setResponse(new JsonResponse([
                'error' => [
                    'status_code' => $exception->getStatusCode(),
                    'message' => $exception->getMessage(),
                    'data' => $exception->getData(),
                    'debug' => ($this->debug) ? [
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'previous' => ($exception->getPrevious() !== null) ? [
                            'file' => $exception->getPrevious()->getFile(),
                            'line' => $exception->getPrevious()->getLine()
                        ] : null
                    ] : null
                ]
            ], $exception->getStatusCode(), $exception->getHeaders()));

        } else {
            if(!$this->twig->getLoader()->exists($exception->getTemplate())) {
                return;
            }

            $event->setResponse(new Response($this->twig->render($exception->getTemplate(), [
                'status_code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
                'data' => $exception->getData()
            ]), $exception->getStatusCode(), $exception->getHeaders()));
        }
    }
}
