<?php

namespace mahmoudz\fyberPhpSdk;

use mahmoudz\fyberPhpSdk\Contracts\FyberInterface;
use mahmoudz\fyberPhpSdk\Exceptions\MissingApiKeyException;
use mahmoudz\fyberPhpSdk\Exceptions\MissingRequiredDataException;

/**
 * Class Fyber
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Fyber implements FyberInterface
{

    public $api_key;

    protected $base_url;

    protected $api_version;

    protected $response_format;

    private $httpClient;

    private $config;

    /**
     * Fyber constructor.
     *
     * @param \mahmoudz\fyberPhpSdk\HttpClient $httpClient
     * @param \mahmoudz\fyberPhpSdk\Config     $config
     */
    public function __construct(HttpClient $httpClient, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;

        $this->readConfig();
    }


    /**
     * refer the docs for what needs to be passed in:
     *      http://developer.fyber.com/content/current/android/offer-wall/offer-api/
     *      http://developer.fyber.com/content/current/ios/offer-wall/offer-api/
     *
     * @param array $data
     * @param       $appType
     *
     * @return  mixed
     * @throws \mahmoudz\fyberPhpSdk\WrongAppTypeException
     */
    public function getOffers(Array $data, $appType)
    {
        $endpoint = "offers";

        // append the app id based on the app type
        $data['appid'] = $this->getAppId($appType);

        // validate the data is correct and complete
        $data = $this->validateData($data);

        // append the hash key to the data
        $data['hashkey'] = $this->calculateHashKey($data);

        // build the full query parameter from the data
        $parametersQuery = http_build_query($data);

        $uri = $this->base_url
            . "v" . $this->api_version
            . "/" . $endpoint
            . '.' . $this->response_format
            . "?" . $parametersQuery;

        $headers = ['Accept' => 'application/json'];

        $result = $this->httpClient->get($uri, $headers);

        $content = $result->getBody()->getContents();

        $contentObject = json_decode($content);

        return new Offers($contentObject);
    }

    /**
     * Testing function, to see the response without hitting the API
     *
     * @param       $data
     * @param       $appType
     *
     * @return  \mahmoudz\fyberPhpSdk\Offers
     */
    public function getOffersMock($data, $appType)
    {
        $content = file_get_contents(__DIR__ . '/offers-response.txt');

        $contentObject = json_decode($content);

        return new Offers($contentObject);
    }

    /**
     * @param $appType
     *
     * @return  null
     * @throws \mahmoudz\fyberPhpSdk\WrongAppTypeException
     */
    private function getAppId($appType)
    {
        $appId = null;

        switch (strtolower($appType)) {
            case 'android':
                $appId = $this->android_app_id;
                break;
            case 'ios':
                $appId = $this->android_app_id;
                break;
            case 'web':
                $appId = $this->android_app_id;
                break;
            default:
                throw new WrongAppTypeException('Supported App types are ("android", "ios" and "web").');
        }

        return $appId;
    }

    /**
     * @param $data
     *
     * @return  array
     */
    private function validateData($data)
    {
        $allPossibleData = [
            'appid'                                 => null,
            'uid'                                   => null,
            'locale'                                => null,
            'device_id'                             => null,
            'os_version'                            => null,
            'timestamp'                             => null,
            'ip'                                    => null,
            'pub0'                                  => null,
            'page'                                  => null,
            'offer_types'                           => null,
            'ps_time'                               => null,
            'device'                                => null,
            'google_ad_id'                          => null,
            'google_ad_id_limited_tracking_enabled' => null,
            'apple_idfa'                            => null,
            'apple_idfa_tracking_enabled'           => null,
        ];

        // Fill missing data input with null, by merging it with the $allPossibleData
        $data = array_replace_recursive($allPossibleData, $data);

        // remove all null values, in case provided by the input or was missing form the input
        $data = array_filter($data);

        $required = ['appid', 'uid', 'locale', 'device_id', 'os_version', 'timestamp'];

        if (!$this->requiredFieldsExist($required, $data)) {
            throw new MissingRequiredDataException();
        }

        return $data;
    }

    /**
     * @param $required
     * @param $data
     *
     * @return  bool
     */
    private function requiredFieldsExist($required, $data)
    {
        if (count(array_intersect_key(array_flip($required), $data)) === count($required)) {
            //All required keys exist!
            return true;
        }

        return false;
    }

    /**
     * @param array $parametersArray
     *
     * @return  string
     */
    private function calculateHashKey(Array $parametersArray)
    {
        // 1. Order all request alphabetically
        ksort($parametersArray);

        // 2. Concatenate all request parameters
        $parametersQuery = http_build_query($parametersArray);

        if (!$this->api_key) {
            throw new MissingApiKeyException('Missing API KEY');
        }

        // 3. Concatenate the resulting string with the API Key
        $parametersQueryWithKey = $parametersQuery . '&' . $this->api_key;

        // 4. Hash the resulting string using SHA1
        $hashKey = sha1($parametersQueryWithKey);

        return $hashKey;
    }

    /**
     * Read the values from the config file
     */
    private function readConfig()
    {
        // TODO: validate configurations not missed and replace it with null if so
        $this->base_url = $this->config->get('base_url');
        $this->api_version = $this->config->get('api_version');
        $this->response_format = $this->config->get('response_format');
        $this->api_key = $this->config->get('api_key');

        $this->android_app_id = $this->config->get('android_app_id');
        $this->ios_app_id = $this->config->get('ios_app_id');
        $this->web_app_id = $this->config->get('web_app_id');
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->api_key;
    }

    /**
     * with this you can override the config key
     *
     * @param  mixed $key
     */
    public function setKey($key)
    {
        $this->api_key = $key;

        return $this;
    }

}
