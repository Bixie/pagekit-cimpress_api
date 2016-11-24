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

    const CACHE_TIME = 36;

    const CACHE_KEY_PRODUCTS = 'bixie.cimpress_api.products';

    const CACHE_KEY_PRICES = 'bixie.cimpress_api.prices';


    /**
     * @Route("/", methods="GET")
     * @Request({"filter": "array", "page": "int"})
     */
    public function indexAction ($filter = [], $page = 0) {

        $filter = array_merge(array_fill_keys(['search', 'order', 'limit'], ''), $filter);

        extract($filter, EXTR_SKIP);

        if ($cached = App::cache()->fetch(self::CACHE_KEY_PRODUCTS) and $cached['timestamp'] > (time() - self::CACHE_TIME)) {

            $product_response = $cached['products'];

        } else {

            try {

                /** @var Response $response */
                $response = App::cimpress_api()->get('v1', 'partner/products', []);
                if (!$product_response = $response->getData()) {

                    throw new CimpressApiException($response->getError());

                }

                App::cache()->save(self::CACHE_KEY_PRODUCTS, ['timestamp' => time(), 'products' => $product_response]);

            } catch (CimpressApiException $e) {
                return App::abort(500, $e->getMessage());
            }

        }


        if (!empty($search)) {
            $product_response = array_filter($product_response, function ($product) use ($search) {
                return stripos($product['Sku'] . $product['ProductName'], $search) !== false;
            });
        }

        $limit = (int)$limit ?: 20;
        $count = count($product_response);
        $pages = ceil($count / $limit);
        $page = max(0, min($pages - 1, $page));

        $products = array_slice($product_response, ($page * $limit), $limit);

        return compact('products', 'pages', 'count');

    }

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


            if ($cached = App::cache()->fetch(self::CACHE_KEY_PRICES) and $cached['timestamp'] > (time() - self::CACHE_TIME)) {

                $product_prices = $cached['prices'];

            } else {

                /** @var Response $response */
                $response = App::cimpress_api()->get('v1', 'partner/product-prices', []);
                if (!$product_prices_response = $response->getData()) {

                  throw new CimpressApiException($response->getError());

                }
                $product_prices = [];
                foreach ($product_prices_response as $product_price) {
                    $product_prices[$product_price['Sku']][] = $product_price;
                }

                App::cache()->save(self::CACHE_KEY_PRODUCTS, ['timestamp' => time(), 'prices' => $product_prices]);
            }

            if (!$prices = $product_prices[$sku]) {

                throw new CimpressApiException(sprintf('No prices for Sku %s', $sku));

            }


            /** @var Response $response */
            $response = App::cimpress_api()->get('v1', sprintf('products/%s/surfaces', $sku));
            if (!$surfaces = $response->getData()) {

                throw new CimpressApiException($response->getError());
            }

            return compact('surfaces', 'prices');

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
