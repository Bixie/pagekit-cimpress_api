<?php

namespace Bixie\CimpressApi\Controller;

use Bixie\CimpressApi\Request\Response;
use Bixie\Game2art\Model\Product;
use Pagekit\Application as App;

/**
 * @Access(admin=true)
 */
class CimpressApiController {

	/**
	 * @Route("/", methods="GET")
	 */
	public function indexAction () {
        $data = [
            'config' => App::module('bixie/cimpress_api')->config()
        ];
        /** @var Response $response */
        $response = App::cimpress_api()->get('v1', 'livecheck');
        if ($livecheck = $response->getData()) {

            $data['livecheck'] = $livecheck;

        } else {
            $data['error'] = $response->getError();
        }
        /** @var Response $response */
        $response = App::cimpress_api()->get('v1', 'partner/fulfillment-status');
        if ($status = $response->getData()) {

            $data['status'] = $status;

        } else {
            $data['error'] = $response->getError();
        }
        $game2art_products = [];
        foreach (Product::findAll() as $product) {
            $game2art_products[$product->sku] = $product;
        };
        $data['game2art_products'] = $game2art_products;
        $data['default_product'] = Product::create();

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
