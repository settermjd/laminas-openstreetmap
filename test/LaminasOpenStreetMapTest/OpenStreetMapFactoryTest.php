<?php

declare(strict_types=1);

namespace LaminasOpenStreetMapTest;

use LaminasOpenStreetMap\OpenStreetMapFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class OpenStreetMapFactoryTest extends TestCase
{
    /**
     * @dataProvider openStreetMapConfigDataProvider
     */
    public function testCanInstantiateOpenStreetMapObjectCorrectly(array $config): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true);
        $container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $factory = new OpenStreetMapFactory();
        $object  = $factory($container);

        $this->assertSame(
            $config['openstreetmap']['language'] ?? OpenStreetMapFactory::DEFAULT_LANGUAGE,
            $object->getLanguage()
        );
    }

    /**
     * @return array<string,array<string,string|int>>
     */
    public static function openStreetMapConfigDataProvider(): array
    {
        return [
            [
                [
                    'openstreetmap' => [
                        'timeout'  => 600,
                        'language' => 'en-AU',
                    ],
                ],
            ],
            [
                [
                    'openstreetmap' => [
                        'language' => 'en-GB',
                    ],
                ],
            ],
            [
                [
                    'openstreetmap' => [
                        'timeout' => 600,
                    ],
                ],
            ],
            [
                [
                    'openstreetmap' => [
                        'timeout'  => 600,
                        'language' => 'en',
                    ],
                ],
            ],
        ];
    }
}
