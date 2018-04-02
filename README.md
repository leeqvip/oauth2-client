# OAuth2-Client

OAuth 是针对访问授权的一个开放标准，它正通过许多实现（包括针对Spring Security的一个实现）而不断获得动力。

### 安装

```
composer require techleeone/oauth2-client
```

### 使用

```php
// 如果是使用支持composer自动加载的框架（比如thinkphp，laravel），则无需require。
require_once dirname(__FILE__) . '/vendor/autoload.php';

use TechOne\OAuth2\Client\Provider;

$config = [
    // 应用ID | 公众号的唯一标识appid
    'client_id' => '',
    // 应用私钥 |  公众号的appsecret
    'client_secret' => '',
    // 回调地址
    'redirect_uri' => '',
    // 授权登录页面地址
    'authorize_url' => '',
    // 获取AccessToken地址
    'access_token_url' => '',
];
$token = Provider::create($config)->getAccessToken();
```

#### 微信网页授权

```php
$token = Provider::create($config, 'wechat')->getAccessToken();
```

#### 开源中国（oschina）认证接口

```php
$token = Provider::create($config, 'oschina')->getAccessToken();
```

#### 扩展

您可以自行扩展其他平台的oauth2认证接口，必须继承 `TechOne\OAuth2\Client\Providers\AbstractProvider` 。

例如：

```php

namespace YourNamespace;

use TechOne\OAuth2\Client\Providers\AbstractProvider;
use TechOne\OAuth2\Client\Provider;

class YourOauth2Provider extends AbstractProvider
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

$token = Provider::create($config, '\\YourNamespace\\YourOauth2Provider')->getAccessToken();
```