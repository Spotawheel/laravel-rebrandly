<?php

namespace Spotawheel\Rebrandly\Client;

use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;


class RebrandlyClient
{
    private $client;
    private $api_key;
    private $api_url;

    public function __construct(ClientInterface $client, $api_key, $api_url)
    {
        $this->client = $client;
        $this->api_key = $api_key;
        $this->api_url = $api_url;
    }

    private function event(string $method, string $api_url, array $body = []): mixed
    {
        $header = [
            'apikey' => $this->api_key,
            'Content-Type' => 'application/json',
        ];

        try {
            $request = new Request($method, $api_url, $header, json_encode($body));
            $response = $this->client->send($request);
        
        } catch (RequestException $e) {
            if ($e->getResponse() !== null && $e->getResponse()->getStatusCode() === 401) {
                throw new Exception('Rebrandly - invalid api key');
            } elseif ($e->getResponse() !== null && $e->getResponse()->getStatusCode() === 404) {
                throw new Exception('Rebrandly - url not found');
            }
            throw new Exception('Rebrandly - ' . $e->getMessage());
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || 299 < $statusCode) {
            throw new Exception('Rebrandly - ' . $response->getBody());
        }

        return json_decode($response->getBody()->getContents());
    }

    public function countLinks(): string
    {
        $result = $this->event('GET', $this->api_url . '/links/count');
        return $result->count;
    }

    public function createLink(string $url, string $title = ''): string
    {
        $body = [
            'destination' => $url,
            'title' => $title,
        ];
        $result = $this->event('POST', $this->api_url . '/links/', $body);
        return 'https://' . $result->shortUrl;
    }

    public function searchLink(string $slashtag, string $domain = 'rebrand.ly'): mixed
    {
        $result = $this->event('GET', $this->api_url . '/links?domain.fullName=' . $domain . '&slashtag=' . $slashtag);
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function deleteLink(string $slashtag, string $domain = 'rebrand.ly'): bool
    {
        $result = $this->searchLink($slashtag, $domain);
        if ($result) {
            if ($result->slashtag == $slashtag){
                $this->event('DELETE', $this->api_url . '/links/' . $result->id);
                return true;
            }
        }
        return false;
    }

    public function accountDetails(): object
    {
        return $this->event('GET', $this->api_url . '/account');
    }

}
