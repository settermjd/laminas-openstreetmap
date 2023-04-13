<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result\Search;

use PHPUnit\Framework\TestCase;

class JsonSearchResultFactoryTest extends TestCase
{
    public function testCanHydrateASearchResultObjectFromValidData(): void
    {
        // phpcs:disable Generic.Files.LineLength
        $searchResult = <<<EOF
[{
    "address": {
        "city": "Berlin",
        "city_district": "Mitte",
        "construction": "Unter den Linden",
        "continent": "European Union",
        "country": "Deutschland",
        "country_code": "de",
        "house_number": "1",
        "neighbourhood": "Scheunenviertel",
        "postcode": "10117",
        "public_building": "Kommandantenhaus",
        "state": "Berlin",
        "suburb": "Mitte"
    },
    "boundingbox": [
        "52.5460929870605",
        "52.5460968017578",
        "13.3591794967651",
        "13.3591804504395"
    ],
    "class": "shop",
    "display_name": "B\u00e4cker Kamps, Bahnsteig U6, Sprengelkiez, Wedding, Mitte, Berlin, 13353, Deutschland, European Union",
    "icon": "https://nominatim.openstreetmap.org/images/mapicons/shopping_bakery.p.20.png",
    "importance": 0.201,
    "lat": "52.5460941",
    "licence": "Data \u00a9 OpenStreetMap contributors, ODbL 1.0. https://www.openstreetmap.org/copyright",
    "lon": "13.35918",
    "osm_id": "317179427",
    "osm_type": "node",
    "place_id": "1453068",
    "type": "bakery"
}]
EOF;
        // phpcs:enable

        $factory = new JsonSearchResultFactory();
        $result  = $factory($searchResult);
        $this->assertInstanceOf(JsonSearchResult::class, $result[0]);
        $this->assertSame('Berlin', $result[0]->getAddress()->getCity());
        $this->assertSame('Mitte', $result[0]->getAddress()->getCityDistrict());
    }
}
