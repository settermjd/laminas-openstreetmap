<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result\Search;

use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\BooleanStrategy;

use function array_filter;

use const ARRAY_FILTER_USE_BOTH;

class SearchOptionsFactory
{
    private ReflectionHydrator $hydrator;

    public function __construct()
    {
        $this->hydrator  = new ReflectionHydrator();
        $booleanStrategy = new BooleanStrategy(1, 0);
        $this->hydrator->addStrategy('showAddressDetails', $booleanStrategy);
        $this->hydrator->addStrategy('showExtraTags', $booleanStrategy);
        $this->hydrator->addStrategy('showNameDetails', $booleanStrategy);
        $this->hydrator->addStrategy('deDupeResults', $booleanStrategy);
        $this->hydrator->addStrategy('debug', $booleanStrategy);
    }

    /**
     * Convert the SearchOptions object into an array of the applicable search parameters and their accompanying values
     */
    public function extract(SearchOptions $searchOptions): array
    {
        $this->hydrator->setNamingStrategy(MapNamingStrategy::createFromExtractionMap(
            [
                'showAddressDetails' => 'addressdetails',
                'showExtraTags'      => 'extratags',
                'showNameDetails'    => 'namedetails',
                'deDupeResults'      => 'dedupe',
                'debug'              => 'debug',
            ]
        ));

        return array_filter(
            $this->hydrator->extract($searchOptions),
            fn($value): mixed => $value,
            ARRAY_FILTER_USE_BOTH
        );
    }
}
