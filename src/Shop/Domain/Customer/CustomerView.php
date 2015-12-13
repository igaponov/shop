<?php

namespace Shop\Domain\Customer;

class CustomerView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $street;
    /**
     * @var string
     */
    private $zipCode;

    public static function create($id, $username, $country = '', $city = '', $street = '', $zipCode = '')
    {
        $object = new static;
        $object->id = $id;
        $object->username = $username;
        $object->country = $country;
        $object->city = $city;
        $object->street = $street;
        $object->zipCode = $zipCode;

        return $object;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
}