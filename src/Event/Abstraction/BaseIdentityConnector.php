<?php


namespace Spoonity\Event\Abstraction;


use Spoonity\Exception;
use Doctrine\DBAL\DriverManager;
use Spoonity\Service\IdentityService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class BaseIdentityConnector
 * @package Spoonity\Event\Abstraction
 */
abstract class BaseIdentityConnector
{
    /** @var ContainerInterface  */
    private $container;

    /** @var IdentityService  */
    private $identityService;

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
         */
        if (!$token = $event->getRequest()->attributes->get('oauth_token')) {
            throw new Exception\UnauthorizedException();
        }

        /**
         * use vendor ID to get credentials list from identity service.
         * TODO: replace temp token with param.
         */
        $credentials = $this->identityService->getCredentials(
            $token['vendor']['vendor_id'],
            $this->container->getParameter('temp_auth_key'),
            $this->getCredentialType()
        );

        /**
         * TODO: connect to sandbox.
         */
        if($credentials == null || sizeof($credentials) < 1) {
            throw new Exception\DbException('No usable credentials available for this identity');
        }

        /**
         * replace default connection.
         */
        $credentials = $credentials['items'][0];

        $connection = DriverManager::getConnection([
            'dbname' => $credentials['database_name'],
            'user' => $credentials['username'],
            'password' => $credentials['password'],
            'host' => $credentials['hostname'],
            'driver' => 'pdo_mysql'
        ]);

        $this->container->set('doctrine.dbal.default_connection', $connection);
    }

    /**
     * @return string
     */
    protected abstract function getCredentialType(): string;
}