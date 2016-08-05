<?php

namespace Bixie\CimpressApi\Request;

use Symfony\Component\HttpFoundation\ParameterBag;

class RequestParameters extends ParameterBag
{
    /**
     * RequestParameters constructor.
     * @param array|RequestInterface $parameters
     */
	public function __construct ($parameters) {
	    if ($parameters instanceof RequestInterface) {
            $parameters = $this->normalizeValues($parameters);
        }
		parent::__construct((array) $parameters);
	}

	/**
	 * @param $array
	 * @return mixed
	 */
	protected function normalizeValues ($array) {
		//fix stdclass from json_array
		foreach ($array as &$value) {
			if (gettype($value) == 'object') {
				$value = (array) $value;
			}
			if (is_bool($value)) {
				$value = $value ? 'true': 'false';
			}
			if (is_array($value)) {
				$value = $this->normalizeValues($value);
			}
		}
		return $array;
	}

}
