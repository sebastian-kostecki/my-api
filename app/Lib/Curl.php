<?php

namespace App\Lib;

use App\Lib\Exceptions\ConnectionException;

class Curl
{
    private $handle;

    private $lastCall;

    public $defaultOptions = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => true,
        CURLINFO_HEADER_OUT => true,
    ];

    public function __construct()
    {
        $this->handle = curl_init();
        $this->reset();
    }

    public function __destruct()
    {
        curl_close($this->handle);
    }

    /**
     * @param  int  $seconds
     * @return void
     */
    public function setTimeout($seconds)
    {
        $this->defaultOptions[CURLOPT_CONNECTTIMEOUT] = $seconds;
    }

    /**
     * @return void
     */
    public function enableCookieSession()
    {
        $this->defaultOptions[CURLOPT_COOKIESESSION] = true;
        $this->defaultOptions[CURLOPT_COOKIEJAR] = '';
        $this->defaultOptions[CURLOPT_COOKIEFILE] = '';
    }

    /**
     * @return array
     */
    public function getSessionCookies()
    {
        $cookies = [];
        $list = curl_getinfo($this->handle, CURLINFO_COOKIELIST);
        foreach ($list as $cookie) {
            [,,,,, $name, $value] = explode("\t", $cookie);
            $cookies[$name] = $value;
        }

        return $cookies;
    }

    /**
     * @return array
     */
    public function getLastCall()
    {
        return $this->lastCall;
    }

    /**
     * @return int
     */
    public function getLastHttpCode()
    {
        /** @var int */
        return $this->lastCall['curlInfo']['http_code'];
    }

    /**
     * @return void
     */
    private function reset()
    {
        curl_reset($this->handle);
        curl_setopt_array($this->handle, $this->defaultOptions);

        $this->lastCall = [
            'method' => null,
            'url' => null,
            'requestHeaders' => null,
            'request' => null,
            'responseHeaders' => null,
            'response' => null,
            'curlInfo' => null,
            'time' => null,
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function call(string $method, string $url, string|array|null $body = null, array $options = []): string
    {
        $this->reset();

        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, $method);
        $this->lastCall['method'] = $method;

        curl_setopt($this->handle, CURLOPT_URL, $url);
        $this->lastCall['url'] = $url;

        if (! empty($body)) {
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, $body);
            if (is_array($body)) {
                $this->lastCall['request'] = http_build_query($body);
            } else {
                $this->lastCall['request'] = $body;
            }
        }

        curl_setopt_array($this->handle, $options);

        $startTime = microtime(true);

        $output = curl_exec($this->handle);

        $this->lastCall['time'] = microtime(true) - $startTime;

        $info = curl_getinfo($this->handle);
        $this->lastCall['curlInfo'] = $info;

        if (! empty(curl_error($this->handle))) {
            throw new ConnectionException('Connection Error: '.curl_error($this->handle));
        }

        if ($output === false) {
            throw new ConnectionException('Connection Failed');
        }

        $this->lastCall['requestHeaders'] = $info['request_header'] ?? '';
        $this->lastCall['response'] = substr($output, $info['header_size']);
        $this->lastCall['responseHeaders'] = substr($output, 0, $info['header_size']);

        return $this->lastCall['response'];
    }
}
