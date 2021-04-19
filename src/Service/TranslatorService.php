<?php
/**
 * Created by PhpStorm.
 * User: misfitpixel
 * Date: 5/7/19
 * Time: 12:06 PM
 */

namespace Spoonity\Service;


use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class TranslatorService
 * @package App\Service
 */
class TranslatorService
{
    const LANGUAGE_ENGLISH = 'en';
    const LANGUAGE_FRENCH = 'fr';
    const LANGUAGE_SPANISH = 'es';
    const LANGUAGE_ARABIC = 'ar';
    const LANGUAGE_SWEDISH= 'sv';

    /** @var string */
    private $defaultLanguage;

    /**
     * TranslatorService constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->defaultLanguage = self::LANGUAGE_ENGLISH;

        /**
         * get the default language from the vendor using the OAuth token.
         */
        if($request->getCurrentRequest()->attributes->has('oauth_token')) {
            $token = $request->getCurrentRequest()->attributes->get('oauth_token');

            $this->defaultLanguage = $token['vendor']['language']['code'];
        }
    }

    /**
     * @return string
     */
    public function getDefaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    /**
     * @param string $string
     * @param string|null $languageCode
     * @return string|null
     */
    public function translate(string $string, ?string $languageCode): ?string
    {
        $response = null;
        $data = json_decode($string, true);

        if(NULL === $data || false === is_array($data) || sizeOf($data) === 0) {
            $response = $string;

        } elseif(isset($data[$languageCode])) {
            $response = $data[$languageCode];

        } elseif(isset($data[$this->getDefaultLanguage()])) {
            $response = $data[$this->getDefaultLanguage()];

        }

        return $response;
    }
}