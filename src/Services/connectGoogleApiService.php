<?php


namespace App\Services;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class connectGoogleApiService
{
    CONST URL = 'https://maps.googleapis.com/maps/api/geocode/json?';

    CONST API_KEY = 'AIzaSyChDtqFD104DaO6jVhw7337uW4m6V6FJrY';

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public function getCityLatLong(string $city): array
    {
        $url = self::URL . "address=" . $city . "&key=" . self::API_KEY;
        $response = $this->client->request(
            'GET',
            $url
        );
        $data = $response->toArray();
        return $data['results'][0]['geometry']['location'];
    }

}