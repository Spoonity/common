<?php

namespace Spoonity\Service;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MarketingService
 * @package Spoonity\Service
 */
class MarketingService
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
     * @param int $identityId
     * @param string $fromEmail
     * @param string $fromName
     * @param string $subject
     * @param string $body
     * @param string $to
     * @return bool
     * @throws \Exception
     */
    public function sendEmail(int $identityId, string $fromEmail, string $fromName, string $subject, string $body, string $to): bool
    {
        $ch = curl_init();

        $content = json_encode([
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'subject' => $subject,
            'body' => $body,
            'recipients' => [
                [
                    'email_address' => $to
                ]
            ]
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $this->authenticate()),
            sprintf("Content-Size: %d", strlen($content)),
            sprintf("Identity: %d", $identityId)
        ]);

        curl_setopt($ch, CURLOPT_URL, 'https://marketing.spoonity.com/notifications/email');

        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

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
        $success = true;

        curl_close($ch);

        switch($errorCode) {
            case CURLE_OK:
                break;

            case CURLE_OPERATION_TIMEOUTED:
                throw new \Exception('Could not connect to comms service');

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
                $success = false;
        }

        return $success;
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    private function authenticate(): ?string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://identity.spoonity.com/oauth/token');

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->getRootClientId(),
            'client_secret' => $this->getRootClientSecret(),
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
                throw new \Exception('Could not connect to comms service');

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
                return null;
        }

        if(
            !($token = json_decode($result, true)) ||
            !array_key_exists('access_token', $token)
        ) {
            return null;
        }

        return $token['access_token'];
    }

    /**
     * @return string|null
     */
    private function getRootClientId(): ?string
    {
        return (
            $this->container->hasParameter('oauth') &&
            is_array($this->container->getParameter('oauth')) &&
            array_key_exists('root', $this->container->getParameter('oauth')) &&
            array_key_exists('client_id', $this->container->getParameter('oauth')['root'])
        ) ? $this->container->getParameter('oauth')['root']['client_id'] : null;
    }

    /**
     * @return string|null
     */
    private function getRootClientSecret(): ?string
    {
        return (
            $this->container->hasParameter('oauth') &&
            is_array($this->container->getParameter('oauth')) &&
            array_key_exists('root', $this->container->getParameter('oauth')) &&
            array_key_exists('client_secret', $this->container->getParameter('oauth')['root'])
        ) ? $this->container->getParameter('oauth')['root']['client_secret'] : null;
    }
}
