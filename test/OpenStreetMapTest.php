<?php

declare(strict_types=1);

namespace LaminasTest\OpenStreetMap;

use GuzzleHttp\ClientInterface;
use Laminas\OpenStreetMap\Format\ResponseFormat;
use Laminas\OpenStreetMap\OpenStreetMap;
use Laminas\OpenStreetMap\Result\Search\JsonSearchResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class OpenStreetMapTest extends TestCase
{
    /** @var ResponseInterface&MockObject */
    private $response;

    /** @var ClientInterface&MockObject $client */
    private $client;

    public function setUp(): void
    {
        $this->response = $this->createMock(ResponseInterface::class);
        $this->client   = $this->createMock(ClientInterface::class);
    }

    public function testCanSearchForAndReturnAnAddressInJsonFormat(): void
    {
        $searchQuery = "135 pilkington avenue, birmingham";

        // phpcs:disable Generic.Files.LineLength
        $resultBody = <<<EOF
[{"place_id":128245052,"licence":"Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright","osm_type":"way","osm_id":90394480,"boundingbox":["52.5487473","52.5488481","-1.816513","-1.8163464"],"lat":"52.5487921","lon":"-1.8164308339635031","display_name":"135, Pilkington Avenue, Maney, Sutton Coldfield, Wylde Green, Birmingham, West Midlands Combined Authority, England, B72 1LH, United Kingdom","class":"building","type":"residential","importance":0.41000999999999993,"address":{"house_number":"135","road":"Pilkington Avenue","hamlet":"Maney","town":"Sutton Coldfield","village":"Wylde Green","city":"Birmingham","ISO3166-2-lvl8":"GB-BIR","state_district":"West Midlands Combined Authority","state":"England","ISO3166-2-lvl4":"GB-ENG","postcode":"B72 1LH","country":"United Kingdom","country_code":"gb"},"geojson":{"type":"Polygon","coordinates":[[[-1.816513,52.5487566],[-1.816434,52.5487473],[-1.816429,52.5487629],[-1.8163717,52.5487561],[-1.8163464,52.5488346],[-1.8164599,52.5488481],[-1.8164685,52.5488213],[-1.8164913,52.548824],[-1.816513,52.5487566]]]}}]
EOF;
        // phpcs:enable

        /** @var StreamInterface&MockObject $body */
        $body = $this->createMock(StreamInterface::class);
        $body
            ->expects($this->once())
            ->method('getContents')
            ->willReturn($resultBody);

        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'search',
                [
                    'query' => [
                        'q'               => $searchQuery,
                        'polygon_geojson' => 1,
                        'limit'           => 10,
                        'format'          => ResponseFormat::JSON->value,
                        'accept-language' => 'en-au',
                    ],
                ]
            )
            ->willReturn($this->response);

        $osm = new OpenStreetMap($this->client, 'en-au');

        $this->assertSame($resultBody, $osm->search(
            $searchQuery,
            ResponseFormat::JSON,
            returnRaw: true
        ));
    }

    public function testCanSearchInJsonFormatAndReturnAJsonSearchResultObject(): void
    {
        $searchQuery = "135 pilkington avenue, birmingham";

        // phpcs:disable Generic.Files.LineLength
        $resultBody = <<<EOF
[{"place_id":128245052,"licence":"Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright","osm_type":"way","osm_id":90394480,"boundingbox":["52.5487473","52.5488481","-1.816513","-1.8163464"],"lat":"52.5487921","lon":"-1.8164308339635031","display_name":"135, Pilkington Avenue, Maney, Sutton Coldfield, Wylde Green, Birmingham, West Midlands Combined Authority, England, B72 1LH, United Kingdom","class":"building","type":"residential","importance":0.41000999999999993,"address":{"house_number":"135","road":"Pilkington Avenue","hamlet":"Maney","town":"Sutton Coldfield","village":"Wylde Green","city":"Birmingham","ISO3166-2-lvl8":"GB-BIR","state_district":"West Midlands Combined Authority","state":"England","ISO3166-2-lvl4":"GB-ENG","postcode":"B72 1LH","country":"United Kingdom","country_code":"gb"},"geojson":{"type":"Polygon","coordinates":[[[-1.816513,52.5487566],[-1.816434,52.5487473],[-1.816429,52.5487629],[-1.8163717,52.5487561],[-1.8163464,52.5488346],[-1.8164599,52.5488481],[-1.8164685,52.5488213],[-1.8164913,52.548824],[-1.816513,52.5487566]]]}}]
EOF;
        // phpcs:enable

        /** @var StreamInterface&MockObject $body */
        $body = $this->createMock(StreamInterface::class);
        $body
            ->expects($this->once())
            ->method('getContents')
            ->willReturn($resultBody);

        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'search',
                [
                    'query' => [
                        'q'               => $searchQuery,
                        'polygon_geojson' => 1,
                        'limit'           => 10,
                        'format'          => ResponseFormat::JSON->value,
                        'accept-language' => 'en-au',
                    ],
                ]
            )
            ->willReturn($this->response);

        $osm = new OpenStreetMap($this->client, 'en-au');

        $result = $osm->search($searchQuery, ResponseFormat::JSON);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(JsonSearchResult::class, $result[0]);
    }

    public function testSearchReturnsAnEmptyStringWhenSearchingForANonExistentAddress(): void
    {
        $searchQuery = "135 pilkington avenue, birmingham";

        /** @var StreamInterface&MockObject $body */
        $body = $this->createMock(StreamInterface::class);
        $body
            ->expects($this->once())
            ->method('getContents')
            ->willReturn('');

        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'search',
                [
                    'query' => [
                        'q'               => $searchQuery,
                        'polygon_geojson' => 1,
                        'limit'           => 10,
                        'format'          => ResponseFormat::JSON->value,
                        'accept-language' => 'en-au',
                    ],
                ]
            )
            ->willReturn($this->response);

        $osm = new OpenStreetMap($this->client, 'en-au');

        $this->assertSame('', $osm->search(
            $searchQuery,
            ResponseFormat::JSON,
            returnRaw: true
        ));
    }
}
