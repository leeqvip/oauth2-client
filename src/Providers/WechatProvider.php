<?php

namespace TechOne\OAuth2\Client\Providers;

/**
 * 微信公众平台的oauth2的参数
 *
 * Class WechatProvider
 * @package TechOne\OAuth2\Client\Providers
 * @author TechLee <techlee@qq.com>
 */
class WechatProvider extends AbstractProvider
{
    public function getAuthorizeData()
    {
        return [
            'appid'         => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => $this->responseType,
            'scope'         => 'snsapi_userinfo',
            'state'         => $this->getState() . '#wechat_redirect',
        ];
    }

    public function getAccessTokenData($code)
    {
        return [
            'appid'      => $this->clientId,
            'secret'     => $this->clientSecret,
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
    }

    /**
     *
     * @return array|object|string
     * @throws \Exception
     */
    public function getAccessToken()
    {
        if (!isset($_GET['code'])) {
            $this->authorize();
        }
        return $this->getJson($this->accessTokenUrl, $this->getAccessTokenData($_GET['code']));
    }
}
