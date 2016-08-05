<?php

namespace Bixie\Cimpress\Model;


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
    public $DocumentInstructionSourceVersion;
    /**
     * @var string
     */
    public $DocumentInstructionSourceUrl;
    /**
     * @var string
     */
    public $DocumentId;
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
     * @param string $DocumentInstructionSourceVersion
     * @return Item
     */
    public function setDocumentInstructionSourceVersion ($DocumentInstructionSourceVersion) {
        $this->DocumentInstructionSourceVersion = $DocumentInstructionSourceVersion;
        return $this;
    }

    /**
     * @param string $DocumentInstructionSourceUrl
     * @return Item
     */
    public function setDocumentInstructionSourceUrl ($DocumentInstructionSourceUrl) {
        $this->DocumentInstructionSourceUrl = $DocumentInstructionSourceUrl;
        return $this;
    }

    /**
     * @param string $DocumentId
     * @return Item
     */
    public function setDocumentId ($DocumentId) {
        $this->DocumentId = $DocumentId;
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