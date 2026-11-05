<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\AuthorizationException;

class APIController extends Controller
{
    use ApiResponses, AuthorizesRequests;
    
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble(string $ability, mixed $target): bool
    {
        try {
            $this->authorize($ability, $target);
            return true;
        } catch (AuthorizationException $e) {
            return false;
        }
    }
}