<?php

namespace Bixie\CimpressApi\Event;

use Bixie\Cart\Cart\DeliveryOption;
use Bixie\Cart\CartException;
use Bixie\CimpressApi\CimpressApiModule;
use Pagekit\Application as App;
use Pagekit\Event\EventSubscriberInterface;
use Bixie\Cart\Event\DeliveryOptionsEvent;
use Bixie\CimpressApi\Api\CimpressApiException;

class CartListener implements EventSubscriberInterface {
    /**
     * @var CimpressApiModule
     */
    protected $cimpress;

    /**
     * CartListener constructor.
     * @param $cimpress
     */
    public function __construct ($cimpress) {
        $this->cimpress = $cimpress;
    }

    /**
     * @param DeliveryOptionsEvent $event
     * @throws CartException
     */
    public function onDeliveryOptions ($event) {
        try {
            $cartHandler = $event->getCartHandler();
            $delivery_options = $event->getDeliveryOptions();
            $delivery_address = $cartHandler->getDeliveryAddress();

            $cimpress_items = array_filter($cartHandler->getItems(), function ($cartItem) {
                return $cartItem->get('supplier') == 'cimpress';
            });
            if (!count($cimpress_items)) {
                return;
            }
            $cimpress_options = $this->cimpress->getDeliveryOptions($cimpress_items, $delivery_address);

            $delivery_options['cimpress'] = array_map(function ($delivery_option) {
                return (new DeliveryOption([
                    'id' => $delivery_option['DeliveryOptionId'],
                    'business_days' => $delivery_option['BusinessDays'],
                    'price' => $this->cimpress->getPrice($delivery_option['Price']['Amount'], 'delivery'),
                    'currency' => $delivery_option['Price']['Currency'],
                    'eta_date' => $delivery_option['DeliveryArriveByDate']
                ]));
            }, $cimpress_options);

            $event->setDeliveryOptions($delivery_options);

        } catch (CimpressApiException $e) {
            throw new CartException(sprintf('Error in api: %s', $e->getMessage()), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe () {
        return [
            'bixie.game2art.delivery_options' => 'onDeliveryOptions'
        ];
    }
}
