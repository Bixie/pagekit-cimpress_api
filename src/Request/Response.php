<?php

namespace Bixie\CimpressApi\Request;

use Psr\Http\Message\ResponseInterface;


class Response {
	/**
	 * @var float
	 */
	protected $response_time;
	/**
	 * @var ResponseInterface
	 */
	protected $response;
	/**
	 * @var string
	 */
	protected $reasonPhrase;

    /**
     * Response constructor.
     * @param ResponseInterface $response
     * @param int               $response_time
     */
	public function __construct (ResponseInterface $response, $response_time = 0) {
		$this->response = $response;
		$this->reasonPhrase = $response->getReasonPhrase();
        $this->response_time = $response_time;
    }

    /**
     * @return int
     */
	public function getStatusCode () {
		return $this->response->getStatusCode();
	}

    /**
     * @return \Psr\Http\Message\StreamInterface
     */
	public function getResponseBody () {
		return $this->response->getBody();
	}

    /**
     * @return float
     */
    public function getResponseTime () {
        return $this->response_time;
    }

	/**
	 * @return bool|mixed
	 */
	public function getData () {

		try {

			$return = false;

			$data = json_decode($this->response->getBody(), true);

			if (isset($data['Errors']) && is_array($data['Errors'])) {
			    //explicit errors from Cimpress
				$this->reasonPhrase = implode(', ', array_map(function ($error) {
				    return $error['ErrorMessage'];
				}, $data['Errors']));
			}
			if (isset($data['error'])) {
				$this->reasonPhrase = isset($data['message']) ? $data['message'] : $data['error'];
			}
			if ($data && in_array($this->response->getStatusCode(), [200, 201])) {
				$return = $data;
			}
			return $return;
		} catch (\Exception $e) {
			$this->reasonPhrase = $e->getMessage();
			return false;
		}

	}

	public function getError () {
		return $this->reasonPhrase;
	}


}