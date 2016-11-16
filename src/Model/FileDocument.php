<?php

namespace Bixie\CimpressApi\Model;


use Bixie\CimpressApi\Request\RequestInterface;

class FileDocument implements \ArrayAccess, RequestInterface
{

    use ArrayAccessTrait;
    /**
     * @var string
     */
    public $Sku;
    /**
     * @var array
     */
    public $ImageUrls;

    /**
     * @param string $Sku
     * @return FileDocument
     */
    public function setSku ($Sku) {
        $this->Sku = $Sku;
        return $this;
    }

    /**
     * @param array $Images
     * @return FileDocument
     */
    public function setImages ($Images) {
        $this->ImageUrls = $Images;
        return $this;
    }




}