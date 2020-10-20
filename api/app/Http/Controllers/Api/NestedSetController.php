<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NestedSet;
use Illuminate\Http\Request;

class NestedSetController extends Controller
{
    public function getCollection () {
        $nested_set = NestedSet::withDepth()->get()->toJson(JSON_PRETTY_PRINT);
        return response($nested_set, 200);
    }
}
