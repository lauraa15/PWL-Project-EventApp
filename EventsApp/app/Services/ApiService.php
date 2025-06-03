<?php // app/Services/ApiService.php


namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ApiService
{
    protected $client;
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = env('API_BASE_URL', 'http://localhost:3000/api/');
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function get($endpoint, $data = [])
    {
        try {
            $response = $this->client->get($endpoint, ['query' => $data]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle error
            return ['error' => $e->getMessage()];
        }
    }

    public function post($endpoint, $data = [])
    {
        try {
            $response = $this->client->post($endpoint, ['json' => $data]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle error
            return ['error' => $e->getMessage()];
        }
    }

    // Add put, delete methods as needed
}