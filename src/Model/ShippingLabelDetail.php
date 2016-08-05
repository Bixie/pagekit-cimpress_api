<?php

namespace Bixie\Cimpress\Model;


class ShippingLabelDetail implements \ArrayAccess
{

    use ArrayAccessTrait;
    /**
     * @var Address
     */
    public $ReturnAddress;
    /**
     * @var string
     */
    public $MerchantShippingReference;
    /**
     * @var string
     */
    public $MerchantDisplayName;

    /**
     * @param Address $ReturnAddress
     * @return ShippingLabelDetail
     */
    public function setReturnAddress ($ReturnAddress) {
        $this->ReturnAddress = $ReturnAddress;
        return $this;
    }

    /**
     * @param string $MerchantShippingReference
     * @return ShippingLabelDetail
     */
    public function setMerchantShippingReference ($MerchantShippingReference) {
        $this->MerchantShippingReference = $MerchantShippingReference;
        return $this;
    }

    /**
     * @param string $MerchantDisplayName
     * @return ShippingLabelDetail
     */
    public function setMerchantDisplayName ($MerchantDisplayName) {
        $this->MerchantDisplayName = $MerchantDisplayName;
        return $this;
    }

}