<?php

namespace Bixie\Cimpress\Model;


class Address implements \ArrayAccess
{

    use ArrayAccessTrait;
    /**
     * @var string
     */
    public $FirstName;
    /**
     * @var string
     */
    public $MiddleName;
    /**
     * @var string
     */
    public $LastName;
    /**
     * @var string
     */
    public $CompanyName;
    /**
     * @var string
     */
    public $AddressLine1;
    /**
     * @var string
     */
    public $AddressLine2;
    /**
     * @var string
     */
    public $PostalCode;
    /**
     * @var string
     */
    public $City;
    /**
     * @var string
     */
    public $County;
    /**
     * @var string
     */
    public $StateOrRegion;
    /**
     * @var string
     */
    public $CountryCode;
    /**
     * @var string
     */
    public $Phone;
    /**
     * @var string
     */
    public $PhoneExtension;

    /**
     * @param string $FirstName
     * @return Address
     */
    public function setFirstName ($FirstName) {
        $this->FirstName = $FirstName;
        return $this;
    }

    /**
     * @param string $MiddleName
     * @return Address
     */
    public function setMiddleName ($MiddleName) {
        $this->MiddleName = $MiddleName;
        return $this;
    }

    /**
     * @param string $LastName
     * @return Address
     */
    public function setLastName ($LastName) {
        $this->LastName = $LastName;
        return $this;
    }

    /**
     * @param string $CompanyName
     * @return Address
     */
    public function setCompanyName ($CompanyName) {
        $this->CompanyName = $CompanyName;
        return $this;
    }

    /**
     * @param string $AddressLine1
     * @return Address
     */
    public function setAddressLine1 ($AddressLine1) {
        $this->AddressLine1 = $AddressLine1;
        return $this;
    }

    /**
     * @param string $AddressLine2
     * @return Address
     */
    public function setAddressLine2 ($AddressLine2) {
        $this->AddressLine2 = $AddressLine2;
        return $this;
    }

    /**
     * @param string $PostalCode
     * @return Address
     */
    public function setPostalCode ($PostalCode) {
        $this->PostalCode = $PostalCode;
        return $this;
    }

    /**
     * @param string $City
     * @return Address
     */
    public function setCity ($City) {
        $this->City = $City;
        return $this;
    }

    /**
     * @param string $County
     * @return Address
     */
    public function setCounty ($County) {
        $this->County = $County;
        return $this;
    }

    /**
     * @param string $StateOrRegion
     * @return Address
     */
    public function setStateOrRegion ($StateOrRegion) {
        $this->StateOrRegion = $StateOrRegion;
        return $this;
    }

    /**
     * @param string $CountryCode
     * @return Address
     */
    public function setCountryCode ($CountryCode) {
        $this->CountryCode = $CountryCode;
        return $this;
    }

    /**
     * @param string $Phone
     * @return Address
     */
    public function setPhone ($Phone) {
        $this->Phone = $Phone;
        return $this;
    }

    /**
     * @param string $PhoneExtension
     * @return Address
     */
    public function setPhoneExtension ($PhoneExtension) {
        $this->PhoneExtension = $PhoneExtension;
        return $this;
    }


}