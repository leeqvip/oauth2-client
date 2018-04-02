<?php

namespace TechOne\OAuth2\Client\Providers;

/**
 * 开源中国的oauth2的参数
 *
 * Class OschinaProvider
 * @package TechOne\OAuth2\Client\Providers
 * @author TechLee <techlee@qq.com>
 */
class OschinaProvider extends AbstractProvider
{
    public function getAuthorizeData()
    {
        return [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => $this->responseType,
            'state'         => $this->getState(),
        ];
    }

    public function getAccessTokenData($code)
    {
        return [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirectUri,
            'code'          => $code,
            'dataType'      => 'json',
        ];
    }
}
