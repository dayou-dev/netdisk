<?php

namespace cloudwf\Request\V20170328;

/**
 * @deprecated Please use https://github.com/aliyun/openapi-sdk-php
 *
 * Request of ShopGroupInfo
 *
 * @method string getGid()
 */
class ShopGroupInfoRequest extends \RpcAcsRequest
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
            'cloudwf',
            '2017-03-28',
            'ShopGroupInfo',
            'cloudwf'
        );
    }

    /**
     * @param string $gid
     *
     * @return $this
     */
    public function setGid($gid)
    {
        $this->requestParameters['Gid'] = $gid;
        $this->queryParameters['Gid'] = $gid;

        return $this;
    }
}
