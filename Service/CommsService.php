<?php

namespace Spoonity\Common\Service;


use Symfony\Component\HttpFoundation\Response;

/**
 * Class CommsService
 * @package Spoonity\Common\Service
 */
class CommsService
{
    /**
     * @param string $token
     * @param string $fromEmail
     * @param string $fromName
     * @param string $subject
     * @param string $body
     * @param string $to
     * @return bool
     * @throws \Exception
     */
    public function sendEmail(string $token, string $fromEmail, string $fromName, string $subject, string $body, string $to): bool
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
            sprintf('Authorization: Bearer %s', $token),
            sprintf("Content-Size: %d", strlen($content))
        ]);

        curl_setopt($ch, CURLOPT_URL, 'https://comms-na.spoonity.com/notifications/email');

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
     * @param string $token
     * @param string $body
     * @param string $to
     * @return bool
     * @throws \Exception
     */
    public function sendSms(string $token, string $body, string $to): bool
    {
        $ch = curl_init();

        $content = json_encode([
            'type' => 'Transactional',
            'body' => $body,
            'recipients' => [
                [
                    'phone_number' => $to
                ]
            ]
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $token),
            sprintf("Content-Size: %d", strlen($content))
        ]);

        curl_setopt($ch, CURLOPT_URL, 'https://comms-na.spoonity.com/notifications/sms');

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
     * @param string $token
     * @param string $title
     * @param string $body
     * @param int $to
     * @return bool
     * @throws \Exception
     */
    public function sendPush(string $token, string $title, string $body, int $to): bool
    {
        $ch = curl_init();

        $content = json_encode([
            'provider' => 'firebase',
            'title' => $title,
            'body' => $body,
            'recipients' => [
                [
                    'user_id' => $to
                ]
            ]
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $token),
            sprintf("Content-Size: %d", strlen($content))
        ]);

        curl_setopt($ch, CURLOPT_URL, 'https://comms-na.spoonity.com/notifications/push');

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
}
