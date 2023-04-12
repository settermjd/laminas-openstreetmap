<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result\Search;

use Laminas\OpenStreetMap\Result\Address;

class JsonSearchResult
{
    private Address $address;
    private string $class;
    private string $displayName;
    private string $latitude;
    private string $longitude;
    private string $placeId;

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getPlaceId(): string
    {
        return $this->placeId;
    }
}
