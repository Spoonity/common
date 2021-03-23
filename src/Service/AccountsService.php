<?php


namespace Spoonity\Service;


use Spoonity\Entity\User;
use Spoonity\Entity\Vendor;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccountsService
 * @package Spoonity\Service
 */
class AccountsService
{
    /**
     * @param string $token
     * @return array|null
     * @throws \Exception
     */
    public function validateToken(string $token): ?array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $token)
        ]);

        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spoonity.com/api/validate');

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
                throw new \Exception('Could not connect to accounts service');

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
     * @param int $userId
     * @return User|null
     */
    public function getUser(int $userId): ?User
    {
        // TODO: implement me
        return new User(
            $userId,
            'scott@spoonity.com',
            'Scott',
            'Côté',
            ['+16132980281'],
            ['arn:aws:sns:us-east-1:145357958474:endpoint/APNS/106292_CuttersPoint_APNS/c5be10fc-0dc4-3cd8-9740-d13610f86cdc']
        );
    }

    /**
     * @param int $vendorId
     * @return Vendor|null
     */
    public function getVendor(int $vendorId): ?Vendor
    {
        // TODO: implement me
        return new Vendor($vendorId, 'Spoonity', 'support@spoonity.com');
    }
}
