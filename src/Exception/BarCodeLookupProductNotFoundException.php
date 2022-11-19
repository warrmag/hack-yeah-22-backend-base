<?php

namespace App\Exception;

class BarCodeLookupProductNotFoundException extends \Exception
{
    public function __construct(
        string $barCode = "",
    ) {
        parent::__construct(
            message: sprintf("Product with bar code %s not found", $barCode),
            code: 404
        );
    }
}
