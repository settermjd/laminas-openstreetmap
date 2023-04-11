<?php

declare(strict_types=1);

namespace LaminasOpenStreetMap;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use LaminasOpenStreetMap\Format\ResponseFormat;

class OpenStreetMap
{
    public const DEFAULT_RESPONSE_SIZE = 10;

    private string $language;

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function __construct(private readonly ClientInterface $client, string $language)
    {
        $this->language = $language;
    }

    public function search(
        string $searchQuery,
        ResponseFormat $responseFormat,
        int $limit = self::DEFAULT_RESPONSE_SIZE
    ): string {
        $request = $this->client->request(
            'GET',
            'search',
            [
                'query' => [
                    'q'               => $searchQuery,
                    'polygon_geojson' => 1,
                    'limit'           => $limit,
                    'format'          => $responseFormat->value,
                    'accept-language' => $this->language, //$_ENV['openstreetmap.lang'],
                ],
            ]
        );

        return $request->getBody()->getContents();
    }
}
