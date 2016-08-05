<?php

namespace Bixie\CimpressApi;

use Pagekit\Application as App;
use Pagekit\Module\Module;

class CimpressApiModule extends Module {

	/**
	 * {@inheritdoc}
	 */
	public function main (App $app) {

		$app['cimpress_api'] = function ($app) {
		    return new CimpressApi($app, $this->config());
		};


	}


}
