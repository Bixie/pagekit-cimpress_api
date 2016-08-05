<?php

namespace Bixie\CimpressApi\Controller;

use Bixie\Cimpress\Model\Address;
use Bixie\Cimpress\Model\FileDocument;
use Bixie\Cimpress\Model\Item;
use Bixie\Cimpress\Model\Order;
use Bixie\CimpressApi\CimpressApiException;
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
            $response = App::cimpress_api()->get('partner/products', []);
            if ($products = $response->getData()) {

                $return['products'] = $products;

            } else {
                throw new CimpressApiException($response->getError());
            }
            /** @var Response $response */
            $response = App::cimpress_api()->get('partner/product-prices', []);
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
            $response = App::cimpress_api()->get(sprintf('products/%s/surfaces', $sku));
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
     * @Request({"sku": "string", "files": "array", "multipage": "bool"}, csrf=true)
     * @param $sku
     * @param $files
     * @param bool $multipage
     * @return mixed
     */
    public function documentAction ($files, $multipage = false, $sku) {
        $return = [];

        $fileDocument = (new FileDocument())->setSku($sku)->setMultipagePdf($multipage);
        $paths = [];
        foreach ($files as $file) {
            if ($path = App::locator()->find($file)) {
                $paths[] = $path;
            }
        }

        try {

            /** @var Response $response */
            $response = App::cimpress_api()->post('documents/creators/file', $fileDocument, $paths);
            if ($document = $response->getData()) {

                $return['document'] = $document;

            } else {
                throw new CimpressApiException($response->getError());
            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

        return $return;

    }

    /**
     * @param array $cart
     * @return mixed
     */
    public function deliveryOptionsAction ($cart) {
        $return = [];
        try {
            $items = array_map(function ($cartitem) {
                return (new Item())->setSku($cartitem['Sku'])->setQuantity($cartitem['Quantity']);
            }, $cart['items']);

            $order = (new Order())
                ->setDestinationAddress((new Address())->fill($cart['DeliveryAddress']))
                ->setItems($items);

            /** @var Response $response */
            $response = App::cimpress_api()->post('delivery-options', $order);
            if ($delivery_options = $response->getData()) {

                $return['delivery_options'] = $delivery_options['DeliveryOptions'];

            } else {
                throw new CimpressApiException($response->getError());
            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

        return $return;

    }

    /**
     * @param array $cart
     * @return mixed
     */
    public function orderAction ($cart) {
        $return = [];
        try {
            $items = array_map(function ($cartitem) {
                return (new Item())
                    ->setSku($cartitem['Sku'])
                    ->setQuantity($cartitem['Quantity'])
                    ->setDocumentId($cartitem['documents'][0]['DocumentId'])
                    ->setDocumentInstructionSourceUrl($cartitem['documents'][0]['InstructionSourceUrl'])
                    ->setDocumentInstructionSourceVersion($cartitem['documents'][0]['InstructionVersion'])
                    ->setPartnerItemId('')
                    ->setPartnerProductName('')
                    ;
            }, $cart['items']);

            $order = (new Order())
                ->setCustomerId('')
                ->setPartnerOrderId('')
                ->setMetadata('order #')
                ->setDeliveryOptionId($cart['DeliveryOptionId'])
                ->setDestinationAddress((new Address())->fill($cart['DeliveryAddress']))
                ->setItems($items);

            /** @var Response $response */
            $response = App::cimpress_api()->post('orders', $order);
            if ($order = $response->getData()) {

                $return['order'] = $order;

            } else {
                throw new CimpressApiException($response->getError());
            }

        } catch (CimpressApiException $e) {
            App::abort(500, $e->getMessage());
        }

        return $return;

    }

}
