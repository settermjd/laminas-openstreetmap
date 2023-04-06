<?php

declare(strict_types=1);

namespace Settermjd\LaminasOpenStreetMap;

enum ResponseFormat: string
{
    case XML         = 'xml';
    case JSON        = 'json';
    case JSONV2      = 'jsonv2';
    case GEOJSON     = 'geojson';
    case GEOCODEJSON = 'geocodejson';
}
