<?php

namespace TechOne\OAuth2\Client;

use Stringy\Stringy;
use TechOne\OAuth2\Client\Providers\AbstractProvider;
use TechOne\OAuth2\Client\Providers\GenericProvider;
use TechOne\OAuth2\Client\Providers\OschinaProvider;
use TechOne\OAuth2\Client\Providers\WechatProvider;

/**
 * 生成oauth2相应的实例
 *
 * Class Provider
 * @package TechOne\OAuth2\Client
 * @author TechLee <techlee@qq.com>
 */
class Provider
{
    /**
     *
     * @param array $config
     * @param string $provider
     * @return GenericProvider|OschinaProvider|WechatProvider|AbstractProvider
     */
    public static function create(array $config, $provider = 'generic')
    {
        $class = self::getProviderName($provider);
        return new $class($config);
    }

    public static function getProviderName($provider)
    {
        return false !== strpos($provider, '\\') ?
        $provider : '\\TechOne\OAuth2\Client\\Providers\\' . Stringy::create($provider)->upperCamelize($provider) . 'Provider';
    }
}
