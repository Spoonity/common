<?php

namespace Spoonity\Service\Abstraction;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseMicroservice
 * @package Spoonity\Service\Abstraction
 */
class BaseMicroservice
{
    /** @var ContainerInterface  */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    public function authenticate(): ?string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://identity.spoonity.com/oauth/token');

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->container->getParameter('oauth_client_id'),
            'client_secret' => $this->container->getParameter('oauth_client_secret'),
            'scope' => 'root'
        ]));

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $result = (curl_exec($ch));
        $errorCode = curl_errno($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        switch($errorCode) {
            case CURLE_OK:
                break;

            case CURLE_OPERATION_TIMEOUTED:
                throw new \Exception('Could not connect to identity service');

            default:
                throw new \Exception('Error encountered during api request');
        }

        /**
         * set token to null on HTTP failure.
         */
        switch($info['http_code']){
            case Response::HTTP_OK:
            case Response::HTTP_ACCEPTED:
            case Response::HTTP_NO_CONTENT:
                break;

            default:
                $response = json_decode($result, true);
        }

        return isset($response) ? $response['token'] : null;
    }
}
