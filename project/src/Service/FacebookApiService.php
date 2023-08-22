<?php 

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FacebookApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchData()
    {
        $token = "EAAh0GkPJI4cBO3oEAeI2NJUrCB9sRdNPNbN1Ga0CZAvHhoZCuEjCImuRUi1FT3EXdxELwSZCXa1mCUZCUkT6ZAiCJuonG4UwX0weKlF3ZA03kfDTPyVlOaZB9YB1bARGVJ7JRT4gqYMUZCtt4YaZApkWloepmgtdUd6jRf9wqwOmSd5tpsVQTIvfDHxZBXSMZA5pyR2RKpxuPtKluZC2AevfErBf3XP5T3B4OO5BvvG3yuCxqzuKZAF70lqDpww5mZCEC1";
        $response = $this->client->request(
            'GET',
            'https://graph.facebook.com/me/posts?fields=id,message,full_picture,created_time&access_token=' . $token
        );

        return $response;
    }
}