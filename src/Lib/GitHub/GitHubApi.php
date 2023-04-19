<?php

namespace App\Lib\GitHub;

use App\Lib\Enum\State;
use App\Lib\Interfaces\CountApi;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubApi implements CountApi
{
    private State $apiState;
    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
        $this->apiState = State::OK;
    }

    public function getRocksCount($searchTerm): int
    {
        try {
            $rocksResponse = $this->client->request(
                'GET',
                'https://api.github.com/search/issues?q=' . $searchTerm . " rocks"
            );

            return json_decode($rocksResponse->getContent())->total_count;
        } catch (\Exception $e) {
            $this->apiState = State::Error;

            return -1;
        }
    }

    public function getSucksCount($searchTerm): int
    {
        try {
            $sucksResponse = $this->client->request(
                'GET',
                'https://api.github.com/search/issues?q=' . $searchTerm . " sucks"
            );

            return json_decode($sucksResponse->getContent())->total_count;
        } catch (\Exception $e) {
            $this->apiState = State::Error;

            return -1;
        }
    }

    public function getApiStatus(): State
    {
        return $this->apiState;
    }
}
