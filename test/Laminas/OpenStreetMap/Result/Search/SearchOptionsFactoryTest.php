<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result\Search;

use PHPUnit\Framework\TestCase;

class SearchOptionsFactoryTest extends TestCase
{
    /**
     * @dataProvider searchOptionsDataProvider
     */
    public function testCanExtractSearchOptionsArray(array $searchOptionsData, array $expectedResult): void
    {
        $options = new SearchOptions(
            $searchOptionsData['showAddressDetails'] ?? false,
            $searchOptionsData['showExtraTags'] ?? false,
            $searchOptionsData['showNameDetails'] ?? false,
            $searchOptionsData['deDupeResults'] ?? false,
            $searchOptionsData['debug'] ?? false,
        );
        $factory = new SearchOptionsFactory();

        $this->assertSame($expectedResult, $factory->extract($options));
    }

    /**
     * @return array[]
     */
    public function searchOptionsDataProvider(): array
    {
        return [
            [
                [
                    'showAddressDetails' => true,
                    'showExtraTags'      => false,
                    'showNameDetails'    => true,
                ],
                [
                    'addressdetails' => 1,
                    'namedetails'    => 1,
                ],
            ],
            [
                [
                    'showAddressDetails' => false,
                    'showExtraTags'      => false,
                    'showNameDetails'    => false,
                ],
                [],
            ],
        ];
    }
}
