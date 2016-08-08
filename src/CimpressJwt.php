<?php

namespace Bixie\CimpressApi;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Session\Session;

class CimpressJwt
{

    const AUTH_URL = 'https://cimpress.auth0.com/oauth/ro/';
    /**
     * @var Session
     */
    protected $session;

    protected $username;

    protected $password;

    protected $client_id;

    protected $connection = 'default';

    protected $scope = 'openid email app_metadata';

    protected $jwt;

    /**
     * CimpressJwt constructor.
     * @param $session
     * @param array $config
     */
    public function __construct ($session, $config) {
        $this->session = $session;
        $this->username = $config['api_username'];
        $this->password = $config['api_password'];
        $this->client_id = $config['api_client_id'];
        $this->connection = $config['api_connection'];
    }

    /**
     * @return mixed
     * @throws CimpressApiException
     */
    public function getJwt () {
        if ($this->jwt or
           ($this->jwt = $this->session->get('bixie.cimpress.jwt', '') and $this->isJwtValid($this->jwt))) {
            return $this->jwt;
        }
        //bail out if API is not set
        if (!$this->username || !$this->password || !$this->client_id || !$this->connection) {
            throw new CimpressApiException('API config not complete');
        }

        try {
            $this->jwt = $this->getNewJwt();
//            $this->jwt = $this->getNewJWTLegacy();
            $this->session->set('bixie.cimpress.jwt', $this->jwt);

        } catch (RequestException $e) {
            $message = $e->getMessage();
            if ($e->hasResponse()) {
                $message = (string)$e->getResponse()->getBody();
                if ($error = json_decode($message, true)) {
                    $message = $error['error_description'];
                }
            }
            throw new CimpressApiException('Error in requesting JWT: ' . $message, $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new CimpressApiException('Error in requesting JWT: ' . $e->getMessage(), $e->getCode(), $e);
        }
        return $this->jwt;
    }

    protected function getNewJwt(){
        $client = new Client(['base_uri' => self::AUTH_URL]);
        $resp = $client->post('', ['json' => [
            'username' => $this->username,
            'password' => $this->password,
            'client_id' => $this->client_id,
            'connection' => $this->connection,
            'scope' => $this->scope
        ]]);

        if ($jwt = json_decode($resp->getBody())->id_token) {
            return $jwt;
        } else {
            throw new CimpressApiException('Error in decoding body JWT: ' . $resp->getBody());
        }

    }

    protected function isJwtValid($jwt){
        if(strlen($jwt) == 0 || $jwt == null){
            return false;
        }
        $var = base64_decode($jwt);
        preg_match_all('/
        \{              # { character
            (?:         # non-capturing group
                [^{}]   # anything that is not a { or }
                |       # OR
                (?R)    # recurses the entire pattern
            )*          # previous group zero or more times
        \}              # } character
        /x',
            $var, $json);
        $output = json_decode($json[0][1], true);
        $expTime= $output['exp'];
        if($expTime <= time()){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getUsername () {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return CimpressJwt
     */
    public function setUsername ($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword () {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return CimpressJwt
     */
    public function setPassword ($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientId () {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     * @return CimpressJwt
     */
    public function setClientId ($client_id) {
        $this->client_id = $client_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnection () {
        return $this->connection;
    }

    /**
     * @param string $connection
     * @return CimpressJwt
     */
    public function setConnection ($connection) {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope () {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return CimpressJwt
     */
    public function setScope ($scope) {
        $this->scope = $scope;
        return $this;
    }

}