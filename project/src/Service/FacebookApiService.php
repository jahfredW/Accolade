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
        $response = $this->client->request(
            'GET',
            'https://graph.facebook.com/me/posts?fields=id,message,full_picture,created_time&access_token=EAADkmPmkiVYBO1RnXuPxDBtZAzo6LVD7bKotlAsOEekE1EyIbqeqmvIM7YMKCcHIsyIIoyZA9ZAzRreYXZABVgadUZAGO9bwdZBjS3obNOoJ6H1xGdAribCoSEEPUwWYKe3BmEUA4f6rUKPTSzvHlGZBRPyrodW1qC2AnpkFZBBHsYbmmoLKCNaSVCE0pxw9ZCWTnNeK1oxcNZBVWwv9w2CjhRH2DQZCZAAPjG0IlHR43ZBA9GGha9gBZB3isOaoOZAavucnFzwz8pdiwZDZD'
        );

        return $response;
    }
}