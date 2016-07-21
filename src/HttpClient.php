<?php
namespace mahmoudz\fyberPhpSdk;

use GuzzleHttp\Client;

/**
 * Class HttpClient
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class HttpClient
{

    /**
     * @var  \mahmoudz\fyberPhpSdk\Client
     */
    public $client;

    /**
     * HttpClient constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $uri
     *
     * @return  \Psr\Http\Message\ResponseInterface
     */
    public function get($uri)
    {
        return $this->client->get($uri);
    }

}
