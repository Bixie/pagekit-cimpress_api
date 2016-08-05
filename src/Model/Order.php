<?php

namespace Bixie\Cimpress\Model;


use Bixie\Cimpress\Request\RequestInterface;

class Order implements \ArrayAccess, RequestInterface
{

    use ArrayAccessTrait;
    /**
     * @var string
     */
    public $CustomerId;
    /**
     * @var Item[]
     */
    public $Items;
    /**
     * @var Address
     */
    public $DestinationAddress;
    /**
     * @var string
     */
    public $Metadata;
    /**
     * @var string
     */
    public $DeliveryOptionId;
    /**
     * @var ShippingLabelDetail
     */
    public $ShippingLabelDetail;
    /**
     * @var string
     */
    public $PartnerOrderId;

    /**
     * @param string $CustomerId
     * @return Order
     */
    public function setCustomerId ($CustomerId) {
        $this->CustomerId = $CustomerId;
        return $this;
    }

    /**
     * @param Item[] $Items
     * @return Order
     */
    public function setItems ($Items) {
        $this->Items = $Items;
        return $this;
    }

    /**
     * @param Address $DestinationAddress
     * @return Order
     */
    public function setDestinationAddress ($DestinationAddress) {
        $this->DestinationAddress = $DestinationAddress;
        return $this;
    }

    /**
     * @param string $Metadata
     * @return Order
     */
    public function setMetadata ($Metadata) {
        $this->Metadata = $Metadata;
        return $this;
    }

    /**
     * @param string $DeliveryOptionId
     * @return Order
     */
    public function setDeliveryOptionId ($DeliveryOptionId) {
        $this->DeliveryOptionId = $DeliveryOptionId;
        return $this;
    }

    /**
     * @param ShippingLabelDetail $ShippingLabelDetail
     * @return Order
     */
    public function setShippingLabelDetail ($ShippingLabelDetail) {
        $this->ShippingLabelDetail = $ShippingLabelDetail;
        return $this;
    }

    /**
     * @param string $PartnerOrderId
     * @return Order
     */
    public function setPartnerOrderId ($PartnerOrderId) {
        $this->PartnerOrderId = $PartnerOrderId;
        return $this;
    }

}