<?php

namespace Bixie\CimpressApi\Model;


class Item implements \ArrayAccess
{

    use ArrayAccessTrait;
    /**
     * @var string
     */
    public $Sku;
    /**
     * @var string
     */
    public $DocumentReferenceUrl;
    /**
     * @var int
     */
    public $Quantity;
    /**
     * @var string
     */
    public $PartnerProductName;
    /**
     * @var string
     */
    public $PartnerItemId;

    /**
     * @param string $Sku
     * @return Item
     */
    public function setSku ($Sku) {
        $this->Sku = $Sku;
        return $this;
    }

    /**
     * @param string $DocumentReferenceUrl
     * @return Item
     */
    public function setDocumentReferenceUrl ($DocumentReferenceUrl) {
        $this->DocumentReferenceUrl = $DocumentReferenceUrl;
        return $this;
    }

    /**
     * @param int $Quantity
     * @return Item
     */
    public function setQuantity ($Quantity) {
        $this->Quantity = $Quantity;
        return $this;
    }

    /**
     * @param string $PartnerProductName
     * @return Item
     */
    public function setPartnerProductName ($PartnerProductName) {
        $this->PartnerProductName = $PartnerProductName;
        return $this;
    }

    /**
     * @param string $PartnerItemId
     * @return Item
     */
    public function setPartnerItemId ($PartnerItemId) {
        $this->PartnerItemId = $PartnerItemId;
        return $this;
    }

}