<?php

namespace Bixie\CimpressApi\Request;

use Psr\Http\Message\ResponseInterface;


class Response {
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
	 */
	public function __construct (ResponseInterface $response) {
		$this->response = $response;
		$this->reasonPhrase = $response->getReasonPhrase();
	}

	public function getStatusCode () {
		return $this->response->getStatusCode();
	}

	public function getResponseBody () {
		return $this->response->getBody();
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