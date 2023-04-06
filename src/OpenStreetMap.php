<?php

declare(strict_types=1);

namespace Settermjd\LaminasOpenStreetMap;

use GuzzleHttp\ClientInterface;

class OpenStreetMap
{
    public const DEFAULT_RESPONSE_SIZE = 10;

    private string $api = 'https://nominatim.openstreetmap.org/';
    private string $language;

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
