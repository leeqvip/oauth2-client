<?php

namespace TechOne\OAuth2\Client\Providers;

use Httpful\Request;
use Httpful\Response;
use Stringy\Stringy;

/**
 *
 * Class AbstractProvider
 * @package TechOne\OAuth2\Client\Providers
 * @author TechLee <techlee@qq.com>
 */
abstract class AbstractProvider
{

    /**
     * @var string
     */
    public $clientId;

    /**
     * @var string
     */
    public $clientSecret;

    /**
     * @var string
     */
    public $redirectUri;

    /**
     * @var string
     */
    public $responseType = 'code';

    /**
     * 授权页面地址
     *
     * @var string
     */
    public $authorizeUrl = '';

    /**
     * 获取 AccessToken 地址
     *
     * @var string
     */
    public $accessTokenUrl = '';

    /**
     * 重定向后会带上state参数
     *
     * @var null
     */
    protected $state = null;

    public function __construct(array $property = [])
    {
        foreach ($property as $key => $value) {
            $this->attr($key, $value);
        }
    }

    protected function attr($name, $value)
    {
        $attr = $this->s($name)->camelize();
        if (property_exists($this, $attr)) {
            $this->{$attr} = $value;
        }
    }

    /**
     *
     * @param $name
     * @return Stringy
     */
    public function s($name)
    {
        return Stringy::create($name);
    }

    /**
     * 请求授权的参数
     *
     * @return array
     */
    abstract public function getAuthorizeData();

    /**
     * 获取token的参数
     *
     * @param $code
     * @return array
     */
    abstract public function getAccessTokenData($code);

    /**
     * 自动跳转至请求授权地址
     *
     * @return mixed
     */
    public function authorize()
    {
        $query = http_build_query($this->getAuthorizeData());
        header("Location: " . $this->authorizeUrl . '?' . $query);
        exit();
    }

    /**
     * 根据code获取access_token
     *
     * @return array|object|string
     * @throws \Exception
     */
    public function getAccessToken()
    {
        if (!isset($_GET['code'])) {
            $this->authorize();
        }
        return $this->postJson($this->accessTokenUrl, $this->getAccessTokenData($_GET['code']));
    }

    /**
     *
     * @return null|string
     */
    public function getState()
    {
        if ($this->state == null) {
            $this->state = md5(time());
        }
        return $this->state;
    }

    /**
     *
     * @param $url
     * @return array|object|string
     * @throws \Exception
     */
    public function getJson($url, array $data = [])
    {
        return $this->response(
            Request::get($url . '?' . http_build_query($data))
                ->expectsJson()
                ->send()
        );
    }

    /**
     *
     * @param $url
     * @param array $data
     * @return array|object|string
     * @throws \Exception
     */
    public function postJson($url, array $data = [])
    {
        $data = self::json($data);
        return $this->response(
            Request::post($url)
                ->sendsJson()
                ->body($data)
                ->send()
        );
    }

    /**
     *
     * @param Response $response
     * @return array|object|string
     * @throws \Exception
     */
    public function response(Response $response)
    {
        if ($response->code != 200) {
            throw new \Exception("Response Error", $response->code);
        }
        return $response->body;
    }

    /**
     *
     * @param array $data
     * @return string
     */
    public static function json(array $data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    }
}
