<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap;

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

class OpenStreetMapFactory
{
    public const DEFAULT_LANGUAGE = 'en-AU';
    public const DEFAULT_TIMEOUT  = 1;

    public function __invoke(ContainerInterface $container): OpenStreetMap
    {
        /** @var array<string,array<string,string|int>>|null $config */
        $config = $container->has('config')
            ? $container->get('config')
            : null;

        $client = new Client([
            'base_uri' => 'https://nominatim.openstreetmap.org/',
            'timeout'  => $config['openstreetmap']['timeout'] ?? self::DEFAULT_TIMEOUT,
        ]);

        return new OpenStreetMap($client, $config['openstreetmap']['language'] ?? self::DEFAULT_LANGUAGE);
    }
}
