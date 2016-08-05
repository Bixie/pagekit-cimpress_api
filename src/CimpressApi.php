<?php

namespace Bixie\CimpressApi;


use Pagekit\Application as App;
use Bixie\CimpressApi\Request\RequestHeaders;
use Bixie\CimpressApi\Request\RequestInterface;
use Bixie\CimpressApi\Request\RequestParameters;
use Bixie\CimpressApi\Request\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class CimpressApi
{

    const API_URL = 'https://api.cimpress.io/vcs/printapi/';
    const API_VERSION = 'v1';

    protected $app;

    protected $client;
    /**
     * @var array
     */
    protected $config;

    /**
     * @var CookieJar
     */
    protected $cookieJar;

    /**
     * @var CimpressJwt
     */
    protected $jwt;

    /**
     * CimpressApi constructor.
     * @param App $app
     * @param array $config
     */
    public function __construct (App $app, $config = []) {
        $this->app = $app;
        $this->config = $config;
        $this->client = new Client(['base_uri' => self::API_URL . self::API_VERSION . '/']);
        $this->jwt = new CimpressJwt($app['session'], $this->config);
    }

    /**
     * @param string $url
     * @param array|RequestInterface $query
     * @param array $headers
     * @return Response Response from the service.
     */
    public function get ($url, $query = [], $headers = []) {
        return $this->send('GET', $url, $query, [], $headers);
    }

    /**
     * @param string $url
     * @param array|RequestInterface $data
     * @param array $files
     * @param array $headers
     * @return Response Response from the service.
     */
    public function post ($url, $data = [], $files = [], $headers = []) {
        return $this->send('POST', $url, $data, $files, $headers);
    }

    /**
     * @param string $url
     * @param array|RequestInterface $data
     * @param array $files
     * @param array $headers
     * @return Response Response from the service.
     */
    public function put ($url, $data = [], $files = [], $headers = []) {
        return $this->send('PUT', $url, $data, $files, $headers);
    }

    /**
     * @param string $url
     * @param array|RequestInterface $data
     * @param array $files
     * @param array $headers
     * @return Response Response from the service.
     */
    public function delete ($url, $data = [], $files = [], $headers = []) {
        return $this->send('DELETE', $url, $data, $files, $headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $data
     * @param array $files
     * @param array  $headers
     * @return Response Response from the service.
     */
    public function send ($method, $url, $data = [], $files = [], $headers = []) {

        $request_options = [
            'headers' => $this->getHeaders($headers)->all()
        ];

        if (!empty($this->config['debug'])) {
            /** @var \Closure $tapMiddleware */
            $tapMiddleware = Middleware::tap(function ($request) {
                $ct = $request->getHeader('Content-Type');
                $h = $request->getHeaders();
                $b = (string)$request->getBody();
                $u = (string)$request->getUri();
                $e = '';
            });
            $request_options['handler'] = $tapMiddleware($this->client->getConfig('handler'));
        }

        //set data in correct option
        $data = (new RequestParameters($data));
        if ($method == 'GET') {
            //data as query params
            $request_options['query'] = $data->all();
        } else {
                if (count($files)) {
                //multipart form
                $request_options['multipart'] = array_map(function ($filepath) {
                    return [
                        'name'     => 'file',
                        'contents' => fopen($filepath, 'r'),
                        'filename' => basename($filepath)
                    ];
                }, $files);
                foreach ($data->all() as $key => $value) {
                    $request_options['multipart'][] = ['name' => $key, 'contents' => $value];
                }
            } else {
                //regular json data
                $request_options['json'] = $data->all();
            }
        }

        try {
            $response = $this->client->request($method, $url, $request_options);

            return new Response($response);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return new Response($e->getResponse());
            }
            return new Response(new GuzzleResponse(400, [], null, null, $e->getMessage()));

        } catch (CimpressApiException $e) {

            return new Response(new GuzzleResponse(400, [], null, null, $e->getMessage()));
        } catch (\Exception $e) {

            return new Response(new GuzzleResponse(500, [], null, null, $e->getMessage()));
        }

    }

    /**
     * @param array             $headers
     * @return RequestHeaders
     */
    protected function getHeaders ($headers = []) {
        $headers['accept'] = 'application/json';
        $headers['Authorization'] = 'Bearer ' .  $this->jwt->getJwt();
        return new RequestHeaders($headers);
    }

}