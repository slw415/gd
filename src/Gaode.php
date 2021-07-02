<?php
namespace Shishuaishuai\Gaode;
use GuzzleHttp\Client;
use Shishuaishuai\Gaode\Exceptions\InvalidArgumentException;
use Shishuaishuai\Gaode\Exceptions\HttpException;

class Gaode
{
    protected $key;

    protected $guzzleOptions = [];

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getAddress($address, $format = 'json')
    {
        if (!$address) {
            throw new InvalidArgumentException('Invalid response format: ' . $address);
        }

        $url = 'http://restapi.amap.com/v3/geocode/geo?address';

        $query = array_filter([
            'key' => $this->key,
            'address' => $address
        ]);
        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();
            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
