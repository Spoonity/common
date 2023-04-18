<?php

namespace Spoonity\Common\Service;


use Symfony\Component\HttpFoundation\Response;

/**
 * Class IdentityService
 * @package Spoonity\Common\Service
 */
class IdentityService
{
    const CREDENTIAL_TYPE_TRANSACTIONAL = 'transactional';
    const CREDENTIAL_TYPE_WAREHOUSE = 'warehouse';

    /**
     * @param string $token
     * @param int $page
     * @param int $limit
     * @return array|null
     * @throws \Exception
     */
    public function getIdentities(string $token, int $page = 1, int $limit = 10): ?array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $token)
        ]);

        curl_setopt($ch, CURLOPT_URL, sprintf("https://identity.spoonity.com/identities/all?page=%d&limit=%d", $page, $limit));

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

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
                $result = null;
        }

        return ($result != null) ? json_decode($result, true) : null;
    }

    /**
     * @param int $identityId
     * @param string $token
     * @param string $type
     * @return array|null
     * @throws \Exception
     */
    public function getCredentials(int $identityId, string $token, string $type = self::CREDENTIAL_TYPE_TRANSACTIONAL): ?array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $token)
        ]);

        curl_setopt($ch, CURLOPT_URL, sprintf("https://identity.spoonity.com/identities/%d/credentials?q=t:%s",
            $identityId,
            $type
        ));

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

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
                $result = null;
        }

        return ($result != null) ? json_decode($result, true) : null;
    }
}
