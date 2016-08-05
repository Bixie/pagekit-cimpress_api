<?php

namespace Bixie\CimpressApi\Controller;

use Bixie\CimpressApi\Request\Response;
use Pagekit\Application as App;

/**
 * @Access(admin=true)
 */
class CimpressApiController {

	/**
	 * @Route("/", methods="GET")
	 */
	public function indexAction () {
        $data = [];
        /** @var Response $response */
        $response = App::cimpress_api()->get('livecheck');
        if ($livecheck = $response->getData()) {

            $data['livecheck'] = $livecheck;

        } else {
            $data['error'] = $response->getError();
        }
        /** @var Response $response */
        $response = App::cimpress_api()->get('partner/fulfillment-status');
        if ($status = $response->getData()) {

            $data['status'] = $status;

        } else {
            $data['error'] = $response->getError();
        }

		return [
			'$view' => [
				'title' => __('Cimpress API'),
				'name' => 'bixie/cimpress_api/admin/dashboard.php'
			],
			'$data' => $data
		];
	}


	/**
	 * @Access("system: access settings")
	 */
	public function settingsAction () {

		return [
			'$view' => [
				'title' => __('Cimpress Api settings'),
				'name' => 'bixie/cimpress_api/admin/settings.php'
			],
			'$data' => [
				'config' => App::module('bixie/cimpress_api')->config()
			]
		];
	}

	/**
	 * @Request({"config": "array"}, csrf=true)
	 * @Access("system: access settings")
	 */
	public function configAction($config = []) {
		App::config('bixie/cimpress_api')->merge($config, true);

		return ['message' => 'success'];
	}

}
