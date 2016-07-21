<?php
namespace mahmoudz\fyberPhpSdk;

use mahmoudz\fyberPhpSdk\Contracts\FyberInterface;
use mahmoudz\fyberPhpSdk\Exceptions\MissingApiKeyException;

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
    public function __construct(
        HttpClient $httpClient,
        Config $config
    ) {
        $this->httpClient = $httpClient;
        $this->config = $config;

        $this->readConfig();
    }


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
     * @param $data
     */
    public function getOffers(Array $data)
    {
        $endpoint = "offers";

        $parametersArray = $this->getParameters($data);

        // append the hashkey to the parameters
        $parametersArray['hashkey'] = $this->calculateHashKey($parametersArray);

        // build the full query parameter
        $parametersQuery = http_build_query($parametersArray);

        $uri = $this->base_url . "v" . $this->api_version . "/" . $endpoint . '.' . $this->response_format . "?" . $parametersQuery;

        $result = $this->httpClient->get($uri);

        return $result;
    }


    /**
     * @param array $data
     *
     * @return  array
     */
    private function getParameters(Array $data)
    {
        // TODO: validate the data input

        $requiredParameters = [
            // TODO: IMP detect the device type from the request and based on it set the app_id
            'appid'      => $this->android_app_id,
            'uid'        => $data['uid'],
            'locale'     => $data['locale'],
            'device_id'  => $data['device_id'],
            'timestamp'  => $data['timestamp'],
            'os_version' => $data['os_version'],
        ];

        $optionalParameters = [
            'ip'          => $data['ip'],
            'pub0'        => $data['pub0'],
            'page'        => $data['page'],
            'offer_types' => $data['offer_types'],
            'ps_time'     => $data['ps_time'],
            'device'      => $data['offer_types'],
        ];

        // TODO: detect if Android or iPhone

        $androidRequiredParameters = [
            'google_ad_id'                          => $data['google_ad_id'],
            'google_ad_id_limited_tracking_enabled' => $data['google_ad_id_limited_tracking_enabled'],
        ];

//        $iPhoneRequiredParameters = [
//            'apple_idfa'                  => $data['apple_idfa'],
//            'apple_idfa_tracking_enabled' => $data['apple_idfa_tracking_enabled'],
//        ];

        // merge all arrays into one
        $parametersArray = array_merge($requiredParameters, $optionalParameters, $androidRequiredParameters);

        return $parametersArray;
    }

    /**
     * @param $parameters
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

        // 3.Concatenate the resulting string with the API Key
        $parametersQueryWithKey = $parametersQuery . $this->api_key;

        // 4. Hash the resulting string using SHA1
        $hashKey = sha1($parametersQueryWithKey);

        return $hashKey;
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
