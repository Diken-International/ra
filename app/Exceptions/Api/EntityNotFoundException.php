<?php

namespace App\Exceptions\Api;

use App\Helpers\CustomResponse;
use Exception;

class EntityNotFoundException extends Exception
{
    public function render($request)
    {
        return CustomResponse::error($this->getMessage());
    }
}
