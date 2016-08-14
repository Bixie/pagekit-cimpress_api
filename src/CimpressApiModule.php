<?php

namespace Bixie\CimpressApi;

use Bixie\CimpressApi\Api\CimpressApiCacheTrait;
use Bixie\CimpressApi\Api\CimpressApiMethodTrait;
use Pagekit\Application as App;
use Bixie\CimpressApi\Event\CartListener;
use Pagekit\Module\Module;

class CimpressApiModule extends Module {

    use CimpressApiMethodTrait, CimpressApiCacheTrait;

    protected $address_xref = [
        'FirstName' => 'first_name',
        'MiddleName' => 'middle_name',
        'LastName' => 'last_name',
        'CompanyName' => 'company_name',
        'AddressLine1' => 'address1',
        'AddressLine2' => 'address2',
        'PostalCode' => 'zipcode',
        'City' => 'city',
        'County' => 'county',
        'StateOrRegion' => 'state',
        'CountryCode' => 'country_code',
        'Phone' => 'phone',
        'PhoneExtension' => 'mobile'
    ];
	/**
	 * {@inheritdoc}
	 */
	public function main (App $app) {

		$app['cimpress_api'] = function ($app) {
		    return new CimpressApi($app, $this->config());
		};
        $self = $this;
        $app->on('boot', function ($event, $app) use ($self) {
            $app->subscribe(
                new CartListener($self)
            );
        });
    }

    /**
     * get price for delivery and payment options
     * @param $whole_sale_price
     * @param $type
     * @return mixed
     */
    public function getPrice ($whole_sale_price, $type) {
        $price = $whole_sale_price * $this->config['margins'][$type]['factor'];
        $price += $this->config['margins'][$type]['fee'];
        return $price;
    }
}
