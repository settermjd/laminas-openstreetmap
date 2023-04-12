<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result;

class Address
{
    private string $city;
    private string $cityDistrict;
    private string $continent;
    private string $country;
    private string $countryCode;
    private string $houseNumber;
    private string $neighbourhood;
    private string $postcode;
    private string $publicBuilding;
    private string $state;
    private string $suburb;

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCityDistrict(): string
    {
        return $this->cityDistrict;
    }

    public function getContinent(): string
    {
        return $this->continent;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function getNeighbourhood(): string
    {
        return $this->neighbourhood;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function getPublicBuilding(): string
    {
        return $this->publicBuilding;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getSuburb(): string
    {
        return $this->suburb;
    }
}
