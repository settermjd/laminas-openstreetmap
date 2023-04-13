<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result\Search;

use Laminas\Hydrator\NamingStrategy\CompositeNamingStrategy;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Laminas\Hydrator\Strategy\ScalarTypeStrategy;
use Laminas\OpenStreetMap\Result\Address;

use function json_decode;

use const JSON_OBJECT_AS_ARRAY;

class JsonSearchResultFactory
{
    /**
     * @return array<int,JsonSearchResult>
     */
    public function __invoke(string $searchResult = ''): array
    {
        $hydrator = new ReflectionHydrator();
        $hydrator->setNamingStrategy(new CompositeNamingStrategy(
            [
                MapNamingStrategy::createFromHydrationMap([
                    'lon' => 'longitude',
                    'lat' => 'latitude',
                ]),
            ],
            new UnderscoreNamingStrategy()
        ));
        $hydrator->addStrategy('class', ScalarTypeStrategy::createToString());
        $hydrator->addStrategy('displayName', ScalarTypeStrategy::createToString());
        $hydrator->addStrategy('latitude', ScalarTypeStrategy::createToFloat());
        $hydrator->addStrategy('longitude', ScalarTypeStrategy::createToFloat());
        $hydrator->addStrategy('placeId', ScalarTypeStrategy::createToInt());

        $addressHydrator = new ReflectionHydrator();
        $addressHydrator->setNamingStrategy(new UnderscoreNamingStrategy());
        $addressHydrator->addStrategy('city', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('city_district', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('construction', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('continent', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('country', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('country_code', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('house_number', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('neighbourhood', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('postcode', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('public_building', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('state', ScalarTypeStrategy::createToString());
        $addressHydrator->addStrategy('suburb', ScalarTypeStrategy::createToString());

        $hydrator->addStrategy(
            'address',
            new HydratorStrategy(
                $addressHydrator,
                Address::class,
            )
        );

        /** @var array<array<int,mixed>> $response */
        $response      = json_decode($searchResult, associative: true, flags: JSON_OBJECT_AS_ARRAY);
        $searchResults = [];
        foreach ($response as $placeDetails) {
            $searchResults[] = $hydrator->hydrate($placeDetails, new JsonSearchResult());
        }
        return $searchResults;
    }
}
