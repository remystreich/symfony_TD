<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class GoogleBookApiService
{
    private $client;
    private $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function searchBooks(string $title, string $author)
    {
        try {
            $response = $this->client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
                'query' => [
                    'q' => 'intitle:' . $title . '+inauthor:' . $author,
                    'maxResults' => 1,
                    'langRestrict' => "fr",
                ],
            ]);

            return $response->getContent();
        } catch (\Exception $e) {
            $this->logger->error('Error fetching book data: ' . $e->getMessage());

            return [];
        }
    }
}
