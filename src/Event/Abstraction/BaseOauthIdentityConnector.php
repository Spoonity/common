<?php

namespace Spoonity\Event\Abstraction;

use Spoonity\Entity\UserWithIdentity;
use Spoonity\Exception;
use Doctrine\DBAL\DriverManager;
use Spoonity\Service\IdentityService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Class BaseOauthIdentityConnector
 * @package Spoonity\Event\Abstraction
 */
abstract class BaseOauthIdentityConnector
{
    /** @var ContainerInterface  */
    private ContainerInterface $container;

    /** @var IdentityService  */
    private IdentityService $identityService;

    /**
     * BaseIdentityConnector constructor.
     * @param ContainerInterface $container
     * @param IdentityService $identityService
     */
    public function __construct(ContainerInterface $container, IdentityService $identityService)
    {
        $this->container = $container;
        $this->identityService = $identityService;
    }

    /**
     * @param RequestEvent $event
     * @throws \Exception
     */
    public function execute(RequestEvent $event)
    {
        /**
         * verify token exists since it's needed to get credentials.
         * @var PostAuthenticationToken $token
         * @var UserWithIdentity $user
         */
        if (
            null === ($token = $this->container->get('security.token_storage')->getToken()) ||
            null == ($user = $token->getUser())
        ) {
            throw new Exception\UnauthorizedException();
        }

        /**
         * use vendor ID to get credentials list from identity service.
         * TODO: replace temp token with param.
         */
        /** @var  $credentials */
        $credentials = $this->identityService->getCredentials(
            $user->getIdentityId(),
            $this->container->getParameter('temp_auth_key'),
            $this->getCredentialType()
        );

        /**
         * TODO: connect to sandbox.
         */
        if($credentials == null || sizeof($credentials['items']) < 1) {
            throw new Exception\DbException('No usable credentials available for this identity');
        }

        /**
         * replace default connection.
         */
        $credentials = $credentials['items'][0];

        /** @var \Spoonity\DBAL\IdentityDbConnection $connection */
        $connection = $this->container->get('doctrine.dbal.default_connection');

        $connection->reconnect(
            $credentials['database_name'],
            $credentials['username'],
            $credentials['password'],
            (getenv('KUBERNETES_SERVICE_HOST') != null) ? $credentials['proxy_ip'] : $credentials['hostname'],
            (getenv('KUBERNETES_SERVICE_HOST') != null) ? $credentials['proxy_port'] : $credentials['port']
        );
    }

    /**
     * @return string
     */
    protected abstract function getCredentialType(): string;
}
