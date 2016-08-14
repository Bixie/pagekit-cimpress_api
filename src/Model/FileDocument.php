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
     * @var bool
     */
    public $MultipagePdf;

    /**
     * @param string $Sku
     * @return FileDocument
     */
    public function setSku ($Sku) {
        $this->Sku = $Sku;
        return $this;
    }

    /**
     * @param boolean $MultipagePdf
     * @return FileDocument
     */
    public function setMultipagePdf ($MultipagePdf) {
        $this->MultipagePdf = $MultipagePdf;
        return $this;
    }



}