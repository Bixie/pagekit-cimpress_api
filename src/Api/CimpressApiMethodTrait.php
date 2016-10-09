<?php

namespace Bixie\CimpressApi\Api;

use Bixie\CimpressApi\Model\FileDocument;
use Pagekit\Application as App;
use Bixie\Cart\Cart\CartAddress;
use Bixie\Cart\Cart\CartItem;
use Bixie\CimpressApi\Model\Address;
use Bixie\CimpressApi\Model\Item;
use Bixie\CimpressApi\Model\Order;
use Bixie\CimpressApi\Request\Response;


trait CimpressApiMethodTrait {


    /**
     * @param CartItem[]  $products
     * @param CartAddress $address
     * @return mixed
     * @throws CimpressApiException
     */
    public function getDeliveryOptions ($products, $address) {

        $cache_key = 'cimpress.deliveryoptions.' . md5($address->address1.$address->zipcode.$address->city.$address->country_code);

        if ($cached = $this->loadCache($cache_key)) {
            if ($cached['timestamp'] > (time() - (60*60*24))) {
                return $cached['delivery_options'];
            } else {
                $this->removeCache($cache_key);
            }
        }

        $items = array_map(function ($cartitem) {
            return (new Item())->setSku($cartitem->sku)->setQuantity($cartitem->quantity);
        }, $products);

        $address_data = [];
        foreach ($this->address_xref as $cimpress_key => $cart_key) {
            $address_data[$cimpress_key] = $address->$cart_key;
        }

        $order = (new Order())
            ->setDestinationAddress((new Address())->fill($address_data))
            ->setItems(array_values($items));

        /** @var Response $response */
        $response = App::cimpress_api()->post('delivery-options', $order);
        if ($delivery_options = $response->getData()) {

            $this->addCache($cache_key, [
                'timestamp' => time(),
                'delivery_options' => $delivery_options['DeliveryOptions']
            ]);

            return $delivery_options['DeliveryOptions'];

        } else {
            throw new CimpressApiException($response->getError());
        }

    }

    /**
     * @param      $sku
     * @param      $file_urls
     * @return bool|mixed
     */
    public function requestDocument ($sku, $file_urls) {

        $fileDocument = (new FileDocument())->setSku($sku)->setImages($file_urls);

        /** @var Response $response */
        $response = App::cimpress_api()->post('documents/creators/url', $fileDocument);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if ($document = $response->getData()) {

            return $document;

        } else {
            throw new CimpressApiException($response->getError());
        }
    }

    public function createOrder ($order) {
        $address = (array)$order['address'];
        $address_data = [];
        foreach ($this->address_xref as $cimpress_key => $cart_key) {
            $address_data[$cimpress_key] = $address[$cart_key];
        }

        $items = array_map(function ($item) {
            return (new Item())
                ->setSku($item['sku'])
                ->setQuantity($item['quantity'])
                ->setDocumentId($item['DocumentId'])
                ->setDocumentInstructionSourceUrl($item['InstructionSourceUrl'])
                ->setDocumentInstructionSourceVersion($item['InstructionVersion'])
                ->setPartnerItemId($item['product_id'])
                ->setPartnerProductName($item['title']);
        }, $order['items']);

        $order = (new Order())
            ->setCustomerId($order['user_id'])
            ->setPartnerOrderId($order['order_id'])
            ->setMetadata($order['order_reference'])
            ->setDeliveryOptionId($order['delivery_option_id'])
            ->setDestinationAddress((new Address())->fill($address_data))
            ->setItems($items);

        /** @var Response $response */
        $response = App::cimpress_api()->post('orders', $order);
        if ($order = $response->getData()) {

            return $order;

        } else {
            throw new CimpressApiException($response->getError());
        }
    }

}