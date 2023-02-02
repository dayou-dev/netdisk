<?php

namespace Domain_intl\Request\V20171218;

/**
 * @deprecated Please use https://github.com/aliyun/openapi-sdk-php
 *
 * Request of SaveBatchTaskForUpdatingContactInfoByNewContact
 *
 * @method string getCountry()
 * @method string getAddress()
 * @method string getTelArea()
 * @method string getContactType()
 * @method string getCity()
 * @method array getDomainNames()
 * @method string getTelephone()
 * @method string getTransferOutProhibited()
 * @method string getRegistrantOrganization()
 * @method string getTelExt()
 * @method string getProvince()
 * @method string getPostalCode()
 * @method string getUserClientIp()
 * @method string getLang()
 * @method string getEmail()
 * @method string getRegistrantName()
 */
class SaveBatchTaskForUpdatingContactInfoByNewContactRequest extends \RpcAcsRequest
{

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'Domain-intl',
            '2017-12-18',
            'SaveBatchTaskForUpdatingContactInfoByNewContact',
            'domain'
        );
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->requestParameters['Country'] = $country;
        $this->queryParameters['Country'] = $country;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->requestParameters['Address'] = $address;
        $this->queryParameters['Address'] = $address;

        return $this;
    }

    /**
     * @param string $telArea
     *
     * @return $this
     */
    public function setTelArea($telArea)
    {
        $this->requestParameters['TelArea'] = $telArea;
        $this->queryParameters['TelArea'] = $telArea;

        return $this;
    }

    /**
     * @param string $contactType
     *
     * @return $this
     */
    public function setContactType($contactType)
    {
        $this->requestParameters['ContactType'] = $contactType;
        $this->queryParameters['ContactType'] = $contactType;

        return $this;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->requestParameters['City'] = $city;
        $this->queryParameters['City'] = $city;

        return $this;
    }

    /**
     * @param array $domainName
     *
     * @return $this
     */
    public function setDomainNames(array $domainName)
    {
        $this->requestParameters['DomainNames'] = $domainName;
        foreach ($domainName as $i => $iValue) {
            $this->queryParameters['DomainName.' . ($i + 1)] = $iValue;
        }

        return $this;
    }

    /**
     * @param string $telephone
     *
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->requestParameters['Telephone'] = $telephone;
        $this->queryParameters['Telephone'] = $telephone;

        return $this;
    }

    /**
     * @param string $transferOutProhibited
     *
     * @return $this
     */
    public function setTransferOutProhibited($transferOutProhibited)
    {
        $this->requestParameters['TransferOutProhibited'] = $transferOutProhibited;
        $this->queryParameters['TransferOutProhibited'] = $transferOutProhibited;

        return $this;
    }

    /**
     * @param string $registrantOrganization
     *
     * @return $this
     */
    public function setRegistrantOrganization($registrantOrganization)
    {
        $this->requestParameters['RegistrantOrganization'] = $registrantOrganization;
        $this->queryParameters['RegistrantOrganization'] = $registrantOrganization;

        return $this;
    }

    /**
     * @param string $telExt
     *
     * @return $this
     */
    public function setTelExt($telExt)
    {
        $this->requestParameters['TelExt'] = $telExt;
        $this->queryParameters['TelExt'] = $telExt;

        return $this;
    }

    /**
     * @param string $province
     *
     * @return $this
     */
    public function setProvince($province)
    {
        $this->requestParameters['Province'] = $province;
        $this->queryParameters['Province'] = $province;

        return $this;
    }

    /**
     * @param string $postalCode
     *
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->requestParameters['PostalCode'] = $postalCode;
        $this->queryParameters['PostalCode'] = $postalCode;

        return $this;
    }

    /**
     * @param string $userClientIp
     *
     * @return $this
     */
    public function setUserClientIp($userClientIp)
    {
        $this->requestParameters['UserClientIp'] = $userClientIp;
        $this->queryParameters['UserClientIp'] = $userClientIp;

        return $this;
    }

    /**
     * @param string $lang
     *
     * @return $this
     */
    public function setLang($lang)
    {
        $this->requestParameters['Lang'] = $lang;
        $this->queryParameters['Lang'] = $lang;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->requestParameters['Email'] = $email;
        $this->queryParameters['Email'] = $email;

        return $this;
    }

    /**
     * @param string $registrantName
     *
     * @return $this
     */
    public function setRegistrantName($registrantName)
    {
        $this->requestParameters['RegistrantName'] = $registrantName;
        $this->queryParameters['RegistrantName'] = $registrantName;

        return $this;
    }
}
