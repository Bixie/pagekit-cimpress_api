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
     */
    public function onDeliveryOptions ($event) {
        try {
            $cartHandler = $event->getCartHandler();
            if ($delivery_address = $cartHandler->getDeliveryAddress()
                and !!($delivery_address->address1 && $delivery_address->zipcode && $delivery_address->city && $delivery_address->country_code)) {
                $delivery_options = $this->cimpress->getDeliveryOptions($cartHandler->getItems(), $delivery_address);

                $delivery_options = array_map(function ($delivery_option) {
                    return (new DeliveryOption([
                        'id' => $delivery_option['DeliveryOptionId'],
                        'business_days' => $delivery_option['BusinessDays'],
                        'price' => $this->cimpress->getPrice($delivery_option['Price']['Amount'], 'delivery'),
                        'currency' => $delivery_option['Price']['Currency'],
                        'eta_date' => $delivery_option['DeliveryArriveByDate']
                    ]));
                }, $delivery_options);

                $event->setDeliveryOptions($delivery_options);
            }

        } catch (CimpressApiException $e) {
            throw new CartException(sprintf('Error in Cimpress api: %s', $e->getMessage()), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe () {
        return [
            'bixie.cart.delivery_options' => 'onDeliveryOptions'
        ];
    }
}
