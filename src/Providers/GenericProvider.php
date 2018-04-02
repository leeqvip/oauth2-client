<?php

namespace TechOne\OAuth2\Client\Providers;

/**
 * 默认的oauth2的参数
 * Class GenericProvider
 * @package TechOne\OAuth2\Client\Providers
 * @author TechLee <techlee@qq.com>
 */
class GenericProvider extends AbstractProvider
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
            'code'          => $code,
            'redirect_uri'  => $this->redirectUri,
            'state'         => $this->getState(),
            'grant_type'    => 'authorization_code',
        ];
    }
}
