<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NestedSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NestedSetController extends Controller
{
    public function getCollection () {
        $nested_set = NestedSet::withDepth()->get()->toJson(JSON_PRETTY_PRINT);
        return response($nested_set, 200);
    }

    public function updateItem (Request $request, $id) {
        $data = $request->validate([
            "parent_id" => 'required|integer',
            "title" => 'required|string|min:1|max:255',
        ]);

        /** @var $item Model */
        $item = NestedSet::findOrFail($id);
        $item->title = $data['title'];
        $item->parent_id = $data['parent_id'];
        $item->save();

        return response($item->toJson(JSON_PRETTY_PRINT), 200);
    }
}
