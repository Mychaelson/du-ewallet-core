<?php

namespace App\Helpers;

class Location
{
    public $ip;

    public $countryName;

    public $countryCode;

    public $cityName;

    public $stateCode;

    public $stateName;

    public $postalCode;

    public $latitude;

    public $longitude;

    public $timezone;

    public $continent;

    public $currency;

    public function fromIP($ip = null)
    {
        $location = geoip($ip);
        $this->ip = $location->ip;
        $this->countryName = $location->country;
        $this->countryCode = $location->iso_code;
        $this->cityName = $location->city;
        $this->stateCode = $location->state;
        $this->stateName = $location->state_name;
        $this->postalCode = $location->postal_code;
        $this->latitude = $location->lat;
        $this->longitude = $location->lon;
        $this->timezone = $location->timezone;
        $this->continent = $location->continent;
        $this->currency = $location->currency;

        return $this;
    }

    public function fullAddress()
    {
        $address = collect();

        if ($this->cityName && $this->cityName != $this->stateName) {
            $address->add($this->cityName);
        }

        if ($this->stateName) {
            $address->add($this->stateName);
        }

        if ($this->countryName) {
            $address->add($this->countryName);
        }

        return $address->implode(', ');
    }
}
