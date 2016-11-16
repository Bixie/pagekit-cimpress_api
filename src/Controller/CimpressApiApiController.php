<?php

namespace Bixie\CimpressApi\Controller;

use Bixie\CimpressApi\CimpressApiModule;
use Bixie\CimpressApi\Model\Address;
use Bixie\CimpressApi\Model\FileDocument;
use Bixie\CimpressApi\Model\Item;
use Bixie\CimpressApi\Model\Order;
use Bixie\CimpressApi\Api\CimpressApiException;
use Bixie\CimpressApi\Request\Response;
use Pagekit\Application as App;

/**
 * @Access("cimpress_api: use api")
 */
class CimpressApiApiController {
    /**
     * @Route("products", methods="GET")
     */
    public function productsAction () {
        $return = [];
        try {

            /** @var Response $response */
            $response = App::cimpress_api()->get('v1', 'partner/products', []);
            if ($products = $response->getData()) {

                $return['products'] = $products;

            } else {
                throw new CimpressApiException($response->getError());
            }
            /** @var Response $response */
            $response = App::cimpress_api()->get('v1', 'partner/product-prices', []);
            if ($product_prices = $response->getData()) {

                $return['product_prices'] = $product_prices;

            } else {
                throw new CimpressApiException($response->getError());
            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

        return $return;

    }

    /**
     * @Route("product/{sku}", methods="GET", requirements={"sku"="[A-Z]{3}-\d+"})
     * @Request({"sku": "string"})
     * @param $sku
     * @return mixed
     */
    public function productAction ($sku) {
        $return = [];
        try {

            /** @var Response $response */
            $response = App::cimpress_api()->get('v1', sprintf('products/%s/surfaces', $sku));
            if ($surfaces = $response->getData()) {

                $return['surfaces'] = $surfaces;

            } else {
                throw new CimpressApiException($response->getError());
            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

        return $return;

    }

    /**
     * @Route("document/{sku}", methods="POST", requirements={"sku"="[A-Z]{3}-\d+"})
     * @Request({"file_urls": "array", "sku": "string"}, csrf=true)
     * @param $sku
     * @param $file_urls
     * @return mixed
     */
    public function documentAction ($file_urls, $sku) {

        try {

            /** @var CimpressApiModule $cimpress */
            $cimpress = App::module('bixie/cimpress_api');

            if ($document = $cimpress->requestDocument($sku, $file_urls)) {

                return compact('document');

            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

    }


    /**
     * @Route("document/previews", methods="POST")
     * @Request({"Sku": "string", "DocumentReferenceUrl": "string", "width": "int"}, csrf=true)
     * @param     $Sku
     * @param     $DocumentReferenceUrl
     * @param int $width
     * @return mixed
     */
    public function previewsAction ($Sku, $DocumentReferenceUrl, $width = 300) {

        try {

            /** @var CimpressApiModule $cimpress */
            $cimpress = App::module('bixie/cimpress_api');

            if ($previews = $cimpress->requestDocumentPreviews($Sku, $DocumentReferenceUrl, $width)) {

                return $previews;

            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

    }


    /**
     * @Route("/order", methods="POST")
     * @Request({"order": "array"}, csrf=true)
     * @param array $order
     * @return mixed
     */
    public function orderAction ($order) {
        try {

            /** @var CimpressApiModule $cimpress */
            $cimpress = App::module('bixie/cimpress_api');

            if ($order = $cimpress->createOrder($order)) {

                return compact('order');

            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

    }


    /**
     * @Route("/order/{order_id}", methods="GET")
     * @Request({"order_id": "int"})
     * @param array $order_id
     * @return mixed
     */
    public function statusAction ($order_id) {
        try {

            /** @var CimpressApiModule $cimpress */
            $cimpress = App::module('bixie/cimpress_api');

            if ($order = $cimpress->getOrder($order_id)) {

                return compact('order');

            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

    }

}
