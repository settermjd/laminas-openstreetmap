<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Laminas\OpenStreetMap\Format\ResponseFormat;
use Laminas\OpenStreetMap\Result\Search\JsonSearchResult;
use Laminas\OpenStreetMap\Result\Search\JsonSearchResultFactory;

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

    /**
     * Looks up a location from a textual description or address
     *
     * @link https://nominatim.org/release-docs/latest/api/Search/#parameters
     *
     * @param string $searchQuery A free-form query string to search for.
     * @param ResponseFormat $responseFormat Sets the output format of the response.
     * @param int $limit Limits the number of records/matches returned in the request.
     * @param bool $returnRaw Whether to return the raw response from the request or a hydrated object.
     * @return string|array<int,JsonSearchResult>
     * @throws GuzzleException
     */
    public function search(
        string $searchQuery,
        ResponseFormat $responseFormat,
        int $limit = self::DEFAULT_RESPONSE_SIZE,
        bool $returnRaw = false,
    ): string|array {
        $request = $this->client->request(
            'GET',
            'search',
            [
                'query' => [
                    'q'               => $searchQuery,
                    'polygon_geojson' => 1,
                    'limit'           => $limit,
                    'format'          => $responseFormat->value,
                    'accept-language' => $this->language,
                ],
            ]
        );

        $response = $request->getBody()->getContents();
        return $returnRaw
            ? $response
            : (new JsonSearchResultFactory())($response);
    }
}
