<?php

namespace App\Features\Nhgrc;

use Closure;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use GuzzleHttp\RequestOptions;

class Client
{
    public $appKey;
    public $appSecret;

    protected $env = false;

    public function __construct($config, $env = 'prod')
    {
        $this->appKey = $config['app_key'];
        $this->appSecret = $config['app_secret'];

        $this->env = $env;
    }


    
    public function getUrl($url)
    {
        $domain = $this->env == 'prod' ? 'http://www.nhgrc.cn/' : 'http://nhgrc.eepu.top/';
        return $domain . $url;
    }
    public function request($url, $data = [])
    {
        $gatewayUrl = $this->getUrl($url);

        $multipart = $data['multipart'] ?? [];
        $body = $data['body'] ?? [];
        $form_params = $data['form_params'] ?? [];
        $query = $data['query'] ?? [];
        $headers = $data['headers'] ?? [];

        $defaultHeaders = [
            'X-App-Key' => $this->appKey,
            'X-App-Secret' => $this->appSecret,
        ];

        if ($multipart) {
            $options['multipart'] = $multipart;
            $options['headers'] = array_merge($defaultHeaders, $headers);
        } else {
            $options['body'] = $body ? json_encode($body, JSON_UNESCAPED_UNICODE) : '{}';
            $options['form_params'] = $form_params;
            $options['query'] = $query;
            $options['headers'] = array_merge(['Content-Type' => 'application/json'], $defaultHeaders, $headers);
        }

        //发送请求
        $client = new GuzzleClient();
        $response = $client->request('POST', $gatewayUrl, $options);

        $result = $this->getResponse($response, '获取失败');

        return $result;
    }


    /**
     * 处理结果
     *
     * @param object $response
     * @param string $msg
     * @return array
     */
    private function getResponse($response)
    {
        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        if ($result['success'] === false) {
            throw new \Exception($result['msg'] . '-' . $result['code']);
        }

        return $result;
    }
}
